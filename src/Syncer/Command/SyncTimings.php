<?php declare(strict_types=1);

namespace Syncer\Command;

use Syncer\Dto\InvoiceNinja\Task;
use Syncer\Dto\Toggl\TimeEntry;
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
        $projects
    ) {
        $this->togglClient = $togglClient;
        $this->reportsClient = $reportsClient;
        $this->invoiceNinjaClient = $invoiceNinjaClient;
        $this->clients = $clients;
        $this->projects = $projects;
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

            foreach($detailedReport->getData() as $timeEntry) {
                $timeEntrySent = false;

                if (in_array($timeEntry->getId(), $this->sentTimeEntries))
                    continue;

                // Log the entry if the client key exists
                if ($this->timeEntryCanBeLoggedByConfig($this->clients, $timeEntry->getClient(), $timeEntrySent)) {
                    $this->logTask($timeEntry, $this->clients, $timeEntry->getClient());

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
     * @param array $config
     * @param string $key
     *
     * @return void
     */
    private function logTask(TimeEntry $entry, array $config, string $key)
    {
        $task = new Task();

        $task->setDescription($this->buildTaskDescription($entry));
        $task->setTimeLog($this->buildTimeLog($entry));
        $task->setClientId($config[$key]);

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
        $togglClients = $this->togglClient->getClientsForWorkspace($workspaceId);
        $invoiceNinjaClients = $this->invoiceNinjaClient->getClients();

        $clients = Array();

        foreach ($togglClients as $togglClient)
        {
            foreach ($invoiceNinjaClients as $invoiceNinjaClient)
            {
                if (strcasecmp($togglClient->getName(), $invoiceNinjaClient->getName()) == 0)
                    $clients[$invoiceNinjaClient->getName()] = $invoiceNinjaClient->getId();
            }
        }

        return $clients;
    }

    private function retrieveSentTimeEntries()
    {
        if (!file_exists('storage'))
        {
            mkdir('storage');
        }

        if (!file_exists('storage/sent-time-entries'))
        {
            touch('storage/sent-time-entries');
        }
        
        $this->sentTimeEntries = unserialize(file_get_contents('storage/sent-time-entries'));

        if (!is_array($this->sentTimeEntries))
        {
            echo "Done!";
            $this->sentTimeEntries = Array();
        }

        return $this->sentTimeEntries;
    }

    private function storeSentTimeEntries()
    {
        file_put_contents('storage/sent-time-entries', serialize($this->sentTimeEntries));
    }
}
