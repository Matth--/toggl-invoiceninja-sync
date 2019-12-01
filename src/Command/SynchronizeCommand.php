<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Command;

use Matth\Synchronizer\Dto\InvoiceNinja\Client as INClient;
use Matth\Synchronizer\Dto\InvoiceNinja\Project as INProject;
use Matth\Synchronizer\Dto\InvoiceNinja\Task;
use Matth\Synchronizer\Dto\Toggl\DetailedReportLine;
use Matth\Synchronizer\InvoiceNinja\InvoiceNinjaClientInterface;
use Matth\Synchronizer\Toggl\ReportsClientInterface;
use Matth\Synchronizer\Toggl\TogglClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\ItemInterface;

final class SynchronizeCommand extends Command
{
    /**
     * @var TogglClientInterface
     */
    private $togglClient;

    /**
     * @var ReportsClientInterface
     */
    private $reportClient;

    /**
     * @var InvoiceNinjaClientInterface
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
     * @var INClient[]
     */
    private $invoiceNinjaClients;

    /**
     * @var INProject[]
     */
    private $invoiceNinjaProjects;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    public function __construct(
        TogglClientInterface $togglClient,
        ReportsClientInterface $reportClient,
        InvoiceNinjaClientInterface $invoiceNinjaClient,
        CacheItemPoolInterface $cache,
        array $clients,
        array $projects
    ) {
        parent::__construct('synchronize');
        $this->togglClient = $togglClient;
        $this->reportClient = $reportClient;
        $this->invoiceNinjaClient = $invoiceNinjaClient;
        $this->clients = $clients;
        $this->projects = $projects;
        $this->cache = $cache;
    }

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->invoiceNinjaClients = $this->invoiceNinjaClient->getClients();
        $this->invoiceNinjaProjects = $this->invoiceNinjaClient->getProjects();

        foreach ($this->togglClient->getWorkSpaces() as $workSpace) {
            $report = $this->reportClient->getDetailedReport($workSpace->getId());

            foreach ($report->getData() as $reportLine) {

                $cachedItem = $this->cache->getItem('toggl.id.' . $reportLine->getId());

                if ($cachedItem->isHit())  {
                    $io->writeln(sprintf('Toggl entry %d already synchronized to task %d', $reportLine->getId(), (int)$cachedItem->get()));
                    continue;
                }

                if ($task = $this->sendIfConfiguredForProject($reportLine)) {
                    $io->writeln(sprintf(
                        'Saved time entry %d to a configured project',
                        $reportLine->getId()
                    ));
                }

                if (!$task && $task = $this->sendIfConfiguredForClient($reportLine)) {
                    $io->writeln(sprintf(
                        'Saved time entry %d to a configured client',
                        $reportLine->getId()
                    ));
                }

                $cachedItem->set($task->getId());
                // One month
                $cachedItem->expiresAfter(2629743);
                $this->cache->save($cachedItem);
            }
        }

        return 0;
    }

    private function sendIfConfiguredForProject(DetailedReportLine $reportLine): ?Task
    {
        if (! array_key_exists($reportLine->getProject(), $this->projects)) {
            return null;
        }

        $task = new Task();
        $task->setCustomValue1((string) $reportLine->getId());
        $task->setProjectId($this->projects[$reportLine->getProject()]);
        $task->setTimeLog($this->buildTimeLog($reportLine));
        $task->setDescription($reportLine->getDescription());
        return $this->invoiceNinjaClient->saveNewTask($task);
    }


    private function sendIfConfiguredForClient(DetailedReportLine $reportLine): ?Task
    {
        if (! array_key_exists($reportLine->getClient(), $this->clients)) {
            return null;
        }

        $task = new Task();
        $task->setClientId($this->clients[$reportLine->getClient()]);
        $task->setTimeLog($this->buildTimeLog($reportLine));
        $task->setDescription($reportLine->getDescription());
        return $this->invoiceNinjaClient->saveNewTask($task);
    }

    private function buildTimeLog(DetailedReportLine $entry): string
    {
        $timeLog = [[
            $entry->getStart()->getTimestamp(),
            $entry->getEnd()->getTimestamp(),
        ]];

        return \GuzzleHttp\json_encode($timeLog);
    }
}
