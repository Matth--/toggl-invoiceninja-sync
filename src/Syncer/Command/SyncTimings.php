<?php declare(strict_types=1);

namespace Syncer\Command;

use Syncer\Dto\InvoiceNinja\Task;
use Syncer\Dto\Toggl\TimeEntry;
use Syncer\Dto\InvoiceNinja\Client as InvoiceNinjaClientDto;
use Syncer\Dto\InvoiceNinja\Project as InvoiceNinjaProject;
use Syncer\InvoiceNinja\InvoiceNinjaClient;
use Syncer\Toggl\ReportsClient;
use Syncer\Toggl\TogglClient;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SyncTimings
 * @package Syncer\Command
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class SyncTimings extends Command
{
    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var TogglClient
     */
    private $togglClient;

    /**
     * @var TogglClients
     */
    private $togglClients;

    /**
     * @var ReportsClient
     */
    private $reportsClient;

    /**
     * @var InvoiceNinjaClient
     */
    private $invoiceNinjaClient;

    /**
     * @var array
     */
    private $clients;

    /**
     * @var array
     */
    private $projects;

    /**
     * SyncTimings constructor.
     *
     * @param TogglClient $togglClient
     * @param ReportsClient $reportsClient
     * @param InvoiceNinjaClient $invoiceNinjaClient
     * @param array $clients
     * @param array $projects
     */
    public function __construct(
        TogglClient $togglClient,
        ReportsClient $reportsClient,
        InvoiceNinjaClient $invoiceNinjaClient,
        $clients,
        $projects,
        String $storageDir,
        String $storageFileName
    ) {
        $this->togglClient = $togglClient;
        $this->reportsClient = $reportsClient;
        $this->invoiceNinjaClient = $invoiceNinjaClient;
        $this->clients = $clients;
        $this->projects = $projects;
        $this->storageDir = $storageDir;
        $this->storageFileName = $storageFileName;
        $this->retrieveSentTimeEntries();

        parent::__construct();
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('sync:timings')
            ->setDescription('Syncs timings from toggl to invoiceninja')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $workspaces = $this->togglClient->getWorkspaces();

        if (!is_array($workspaces) || count($workspaces) === 0) {
            $this->io->error('No workspaces to sync.');

            return;
        }

        foreach ($workspaces as $workspace) {
            $detailedReport = $this->reportsClient->getDetailedReport($workspace->getId());
            $this->clients = array_merge($this->clients, $this->retrieveClientsForWorkspace($workspace->getId()));
            $this->projects = array_merge($this->projects, $this->retrieveProjectsForWorkspace($workspace->getId()));

            foreach($detailedReport->getData() as $timeEntry) {
                $timeEntrySent = false;

                if (in_array($timeEntry->getId(), $this->sentTimeEntries))
                    continue;

                // Log the entry if the client key exists
                if ($this->timeEntryCanBeLoggedByConfig($this->clients, $timeEntry->getClient(), $timeEntrySent)) {
                    $this->logTask($timeEntry, $this->clients, $timeEntry->getClient(), $this->projects, $timeEntry->getProject());

                    $this->sentTimeEntries[] = $timeEntry->getId();
                    $timeEntrySent = true;
                }

                // Log the entry if the project key exists
                if ($this->timeEntryCanBeLoggedByConfig($this->projects, $timeEntry->getProject(), $timeEntrySent)) {
                    $this->logTask($timeEntry, $this->projects, $timeEntry->getProject());

                    $this->sentTimeEntries[] = $timeEntry->getId();
                    $timeEntrySent = true;
                }

                if ($timeEntrySent) {
                    $this->io->success('TimeEntry ('. $timeEntry->getDescription() . ') sent to InvoiceNinja');
                }
            }
        }

        $this->storeSentTimeEntries();
    }

    /**
     * @param array $config
     * @param string $entryKey
     * @param bool $hasAlreadyBeenSent
     *
     * @return bool
     */
    private function timeEntryCanBeLoggedByConfig(array $config, string $entryKey, bool $hasAlreadyBeenSent): bool
    {
        if ($hasAlreadyBeenSent) {
            return false;
        }

        return (is_array($config) && array_key_exists($entryKey, $config));
    }

    /**
     * @param TimeEntry $entry
     * @param array $clients
     * @param string $clientKey
     *
     * @return void
     */
    private function logTask(TimeEntry $entry, array $clients, string $clientKey, array $projects = NULL, string $projectKey = NULL)
    {
        $task = new Task();

        $task->setDescription($this->buildTaskDescription($entry));
        $task->setTimeLog($this->buildTimeLog($entry));
        $task->setClientId($clients[$clientKey]);

        if (isset($projects) && isset($projectKey))
            $task->setProjectId($projects[$projectKey]);

        $this->invoiceNinjaClient->saveNewTask($task);
    }

    /**
     * @param TimeEntry $entry
     *
     * @return string
     */
    private function buildTaskDescription(TimeEntry $entry): string
    {
        $description = '';

        if ($entry->getProject()) {
            $description .= $entry->getProject() . ': ';
        }

        $description .= $entry->getDescription();

        return $description;
    }

    /**
     * @param TimeEntry $entry
     *
     * @return string
     */
    private function buildTimeLog(TimeEntry $entry): string
    {
        $timeLog = [[
            $entry->getStart()->getTimestamp(),
            $entry->getEnd()->getTimestamp(),
        ]];

        return \GuzzleHttp\json_encode($timeLog);
    }

    private function retrieveClientsForWorkspace($workspaceId)
    {
        $this->togglClients = $this->togglClient->getClientsForWorkspace($workspaceId);
        $invoiceNinjaClients = $this->invoiceNinjaClient->getClients();

        $clients = Array();

        foreach ($this->togglClients as $togglClient)
        {
            $found = false;
            foreach ($invoiceNinjaClients as $invoiceNinjaClient)
            {
                if ($invoiceNinjaClient->getIsDeleted() == false && strcasecmp($togglClient->getName(), $invoiceNinjaClient->getName()) == 0)
                {
                    $clients[$invoiceNinjaClient->getName()] = $invoiceNinjaClient->getId();
                    $found = true;
                }
            }
            if (!$found)
            {
                $client = new InvoiceNinjaClientDto();

                $client->setName($togglClient->getName());

                $clients[$togglClient->getName()] = $this->invoiceNinjaClient->saveNewClient($client)->getId();

                $this->io->success('Client ('. $togglClient->getName() . ') created in InvoiceNinja');
            }
        }

        return $clients;
    }

    private function retrieveProjectsForWorkspace($workspaceId)
    {
        $togglProjects = $this->togglClient->getProjectsForWorkspace($workspaceId);
        $invoiceNinjaProjects = $this->invoiceNinjaClient->getProjects();

        $projects = Array();

        foreach ($togglProjects as $togglProject)
        {
            $found = false;
            foreach ($invoiceNinjaProjects as $invoiceNinjaProject)
            {
                if ($invoiceNinjaProject->getIsDeleted() == false && strcasecmp($togglProject->getName(), $invoiceNinjaProject->getName()) == 0)
                {
                    $projects[$invoiceNinjaProject->getName()] = $invoiceNinjaProject->getId();
                    $found = true;
                }
            }
            if (!$found)
            {
                $project = new InvoiceNinjaProject();

                $project->setName($togglProject->getName());

                foreach ($this->togglClients as $togglClient)
                {
                    if ($togglClient->getWid() == $workspaceId && $togglClient->getId() == $togglProject->getCid())
                        $project->setClientId($this->clients[$togglClient->getName()]);
                }

                $projects[$togglProject->getName()] = $this->invoiceNinjaClient->saveNewProject($project)->getId();

                $this->io->success('Project ('. $togglProject->getName() . ') created in InvoiceNinja');
            }
        }

        return $projects;
    }

    private function retrieveSentTimeEntries()
    {
        if (!file_exists($this->storageDir))
            mkdir($this->storageDir, 0777, true);

        if (!file_exists($this->storageDir . $this->storageFileName))
            touch($this->storageDir . $this->storageFileName);
        
        $this->sentTimeEntries = unserialize(file_get_contents($this->storageDir . $this->storageFileName));

        if (!is_array($this->sentTimeEntries))
            $this->sentTimeEntries = Array();

        return $this->sentTimeEntries;
    }

    private function storeSentTimeEntries()
    {
        file_put_contents($this->storageDir . $this->storageFileName, serialize($this->sentTimeEntries));
    }
}
