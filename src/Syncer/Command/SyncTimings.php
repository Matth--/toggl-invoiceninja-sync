<?php

namespace Syncer\Command;

use Syncer\Toggl\ReportsClient;
use Syncer\Toggl\TogglClient;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\VarDumper;

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
     * SyncTimings constructor.
     *
     * @param SerializerInterface $serializer
     * @param TogglClient $togglClient
     * @param ReportsClient $reportsClient
     * @internal param ReportsClient $client
     * @internal param null|string $testParam
     */
    public function __construct(SerializerInterface $serializer, TogglClient $togglClient, ReportsClient $reportsClient)
    {
        $this->serializer = $serializer;
        $this->togglClient = $togglClient;
        $this->reportsClient = $reportsClient;
        parent::__construct();
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('sync:timings')
            ->setDescription('Syncs timings from toggl to calie invoicing')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $workSpaces = $this->togglClient->getWorkspaces();

        $weeklyReport = $this->reportsClient->getWeeklyReport($workSpaces[0]->getId());

    }
}
