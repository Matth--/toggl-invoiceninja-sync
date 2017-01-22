<?php

namespace Syncer\Command;

use Syncer\Dto\InvoiceNinja\Task;
use Syncer\Dto\Toggl\DetailedReport;
use Syncer\Dto\Toggl\TimeEntry;
use Syncer\Dto\Toggl\Workspace;
use Syncer\InvoiceNinja\Client as InvoiceNinjaClient;
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
    private $projects;

    /**
     * SyncTimings constructor.
     *
     * @param TogglClient $togglClient
     * @param ReportsClient $reportsClient
     * @param InvoiceNinjaClient $invoiceNinjaClient
     * @param array $projects
     */
    public function __construct(
        TogglClient $togglClient,
        ReportsClient $reportsClient,
        InvoiceNinjaClient $invoiceNinjaClient,
        array $projects
    ) {
        $this->togglClient = $togglClient;
        $this->reportsClient = $reportsClient;
        $this->invoiceNinjaClient = $invoiceNinjaClient;
        $this->projects = $projects;

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

        /** @var Workspace $workspace */
        foreach ($workspaces as $workspace) {
            /** @var DetailedReport $detailedReport */
            $detailedReport = $this->reportsClient->getDetailedReport($workspace->getId());

            /** @var TimeEntry $timeEntry */
            foreach($detailedReport->getData() as $timeEntry) {
                if (array_key_exists($timeEntry->getProject(), $this->projects)) {
                    $task = new Task();
                    $task->setDescription($timeEntry->getDescription());
                    $task->setTimeLog(json_encode([[$timeEntry->getStart()->getTimestamp(), $timeEntry->getEnd()->getTimestamp()]]));

                    if ($this->projects[$timeEntry->getProject()]) {
                        $task->setClientId($this->projects[$timeEntry->getProject()]);
                    }

                    $this->invoiceNinjaClient->saveNewTask($task);
                    $this->io->success('Task Created');
                }
            }
        }
    }
}
