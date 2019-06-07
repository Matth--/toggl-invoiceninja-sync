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
     * @var string
     */
    private $storageDir;

    /**
     * @var string
     */
    private $storageFileName;

    /**
     * @var bool
     */
    private $useProjectsAsClients;

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
        String $storageFileName,
        bool $useProjectsAsClients
    ) {
        $this->togglClient = $togglClient;
        $this->reportsClient = $reportsClient;
        $this->invoiceNinjaClient = $invoiceNinjaClient;
        $this->clients = $clients;
        $this->projects = $projects;
        $this->storageDir = $storageDir;
        $this->storageFileName = $storageFileName;
        $this->useProjectsAsClients = $useProjectsAsClients;
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
            $workspaceClients = array_merge($this->clients, $this->retrieveClientsForWorkspace($workspace->getId(), $this->clients));
            $workspaceProjects = array_merge($this->projects, $this->retrieveProjectsForWorkspace($workspace->getId(), $workspaceClients, $this->projects));

            foreach($detailedReport->getData() as $timeEntry) {
                $timeEntrySent = false;
                $timeEntryClient = $timeEntry->getClient();
                $timeEntryProject = $timeEntry->getProject();

                if (in_array($timeEntry->getId(), $this->sentTimeEntries)) {
                    continue;
                }

                // Since all Toggl time entries require there to be a project before there can be a client,
                // a project is required, but a client is not.
                if ($timeEntryProject === null) {
                    $this->io->warning('No project set for TimeEntry (' . $timeEntry->getDescription() . ')');
                }

                else if ($timeEntryClient === null) {
                    if ($this->useProjectsAsClients) {
                        $timeEntryClient = $timeEntryProject;
                        $workspaceClients[$timeEntryProject] = $this->getInvoiceNinjaClientIdForProject($timeEntryProject);
                    } else {
                        $timeEntryProject = null;
                        $this->io->warning('No client set for TimeEntry (' . $timeEntry->getDescription() . ')');
                        $this->io->warning("To allow using projects as clients enable 'use_projects_as_clients' in parameters config file");
                    }
                }

                if ($timeEntryProject !== null) {
                    $this->logTask($timeEntry, $workspaceClients, $timeEntryClient, $workspaceProjects, $timeEntryProject);

                    $this->sentTimeEntries[] = $timeEntry->getId();
                    $timeEntrySent = true;
                } else {
                    $this->logTask($timeEntry);

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
     * @param TimeEntry $entry
     * @param array $clients
     * @param string $clientKey
     *
     * @return void
     */
    private function logTask(TimeEntry $entry, array $clients = NULL, string $clientKey = NULL, array $projects = NULL, string $projectKey = NULL)
    {
        $task = new Task();

        $task->setDescription($this->buildTaskDescription($entry));
        $task->setTimeLog($this->buildTimeLog($entry));
        if (isset($clients) && isset($clientKey)) {
            $task->setClientId($clients[$clientKey]);
        }

        if (isset($projects) && isset($projectKey)) {
            $task->setProjectId($projects[$projectKey]);
        }

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

    /**
     * Retrieve clients from Toggl and match them with their corresponding InvoiceNinja client,
     * or create a new InvoiceNinja client for the Toggl client.
     * 
     * @param  int    $workspaceId
     * @param  array  $workspaceClients
     *
     * @return array
     */
    private function retrieveClientsForWorkspace(int $workspaceId, array $workspaceClients): array
    {
        $togglClients = $this->togglClient->getClientsForWorkspace($workspaceId);
        $invoiceNinjaClients = $this->invoiceNinjaClient->getClients();

        $clients = [];

        foreach ($togglClients as $togglClient) {
            if (!isset($workspaceClients[$togglClient->getName()])) {
                $found = false;
                foreach ($invoiceNinjaClients as $invoiceNinjaClient) {
                    if ($invoiceNinjaClient->getIsDeleted() == false && strcasecmp($togglClient->getName(), $invoiceNinjaClient->getName()) == 0) {
                        $clients[$invoiceNinjaClient->getName()] = $invoiceNinjaClient->getId();
                        $found = true;
                    }
                }
                if (!$found) {
                    $client = new InvoiceNinjaClientDto();

                    $client->setName($togglClient->getName());

                    $clients[$togglClient->getName()] = $this->invoiceNinjaClient->saveNewClient($client)->getId();

                    $this->io->success('Client ('. $togglClient->getName() . ') created in InvoiceNinja');
                }
            }
        }

        return $clients;
    }

    /**
     * Retrieve projects from Toggl and match them with their corresponding InvoiceNinja project,
     * or create a new InvoiceNinja project for the Toggl project.
     * 
     * @param  int    $workspaceId
     * @param  array  $workspaceClients
     * @param  array  $workspaceProjects
     * 
     * @return array
     */
    private function retrieveProjectsForWorkspace(int $workspaceId, array &$workspaceClients, array $workspaceProjects): array
    {
        $togglProjects = $this->togglClient->getProjectsForWorkspace($workspaceId);
        $togglClients = $this->togglClient->getClientsForWorkspace($workspaceId);
        $invoiceNinjaProjects = $this->invoiceNinjaClient->getProjects();

        $projects = [];

        foreach ($togglProjects as $togglProject) {
            if (!isset($workspaceProjects[$togglProject->getName()])) {
                $found = false;
                foreach ($invoiceNinjaProjects as $invoiceNinjaProject) {
                    if ($invoiceNinjaProject->getIsDeleted() == false && strcasecmp($togglProject->getName(), $invoiceNinjaProject->getName()) == 0) {
                        $projects[$invoiceNinjaProject->getName()] = $invoiceNinjaProject->getId();
                        $found = true;
                    }
                }
                if (!$found) {
                    $clientPresent = true;

                    $project = new InvoiceNinjaProject();

                    $project->setName($togglProject->getName());

                    if ($togglProject->getCid() !== null) {
                        foreach ($togglClients as $togglClient) {
                            if ($togglClient->getWid() == $workspaceId && $togglClient->getId() == $togglProject->getCid()) {
                                $project->setClientId($workspaceClients[$togglClient->getName()]);
                            }
                        }
                    } else if ($this->useProjectsAsClients) {
                        $client = new InvoiceNinjaClientDto();

                        $client->setName($togglProject->getName());

                        $workspaceClients[$togglProject->getName()] = $this->invoiceNinjaClient->saveNewClient($client)->getId();

                        $this->io->success('Project ('. $togglProject->getName() . ') created as Client in InvoiceNinja');

                        $project->setClientId($workspaceClients[$togglProject->getName()]);
                    } else {
                        $this->io->error('Client not provided for Project ('. $togglProject->getName() . ') in Toggl');
                        $this->io->warning("To allow using projects as clients enable 'use_projects_as_clients' in parameters config file");
                    }

                    if ($clientPresent) {
                        $projects[$togglProject->getName()] = $this->invoiceNinjaClient->saveNewProject($project)->getId();

                        $this->io->success('Project ('. $togglProject->getName() . ') created in InvoiceNinja');
                    }
                }
            }
        }

        return $projects;
    }

    /**
     * Retrieve the Invoice Ninja client id given the name of a project the client is assigned to.
     * @param  string $projectName
     * @return string
     */
    private function getInvoiceNinjaClientIdForProject(string $projectName): ?string
    {
        $invoiceNinjaProjects  = $this->invoiceNinjaClient->getProjects();
        $invoiceNinjaClientId = null;

        foreach ($invoiceNinjaProjects as $invoiceNinjaProject) {
            if ($invoiceNinjaProject->getName() == $projectName) {
                $invoiceNinjaClientId = $invoiceNinjaProject->getClientId();
            }
        }

        return $invoiceNinjaClientId;
    }

    /**
     * Retrieve log of past sent time entries to prevent sending the same time entries over again.
     * 
     * @return array
     */
    private function retrieveSentTimeEntries(): array
    {
        if (!file_exists($this->storageDir)) {
            mkdir($this->storageDir, 0777, true);
        }

        if (!file_exists($this->storageDir . $this->storageFileName)) {
            touch($this->storageDir . $this->storageFileName);
        }
        
        $this->sentTimeEntries = unserialize(file_get_contents($this->storageDir . $this->storageFileName));

        if (!is_array($this->sentTimeEntries)) {
            $this->sentTimeEntries = [];
        }

        return $this->sentTimeEntries;
    }

    /**
     * Store newly sent time entries into time entry log.
     *
     * @return void
     */
    private function storeSentTimeEntries()
    {
        file_put_contents($this->storageDir . $this->storageFileName, serialize($this->sentTimeEntries));
    }
}
