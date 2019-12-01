<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Command\Toggl;

use Matth\Synchronizer\Toggl\TogglClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListWorkSpacesCommand extends Command
{
    /**
     * @var TogglClientInterface
     */
    private $togglClient;

    public function __construct(TogglClientInterface $togglClient)
    {
        parent::__construct('toggl:list:workspaces');
        $this->togglClient = $togglClient;
    }

    protected function configure()
    {
        $this->setDescription('List Toggl workspaces');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $workSpaces = $this->togglClient->getWorkSpaces();

        $table = new Table($io);
        $table->setHeaders(['Workspace Name', 'Identifier']);

        foreach ($workSpaces as $workSpace) {
            $table->addRow([$workSpace->getName(), $workSpace->getId()]);
        }

        $table->render();

        return 0;
    }
}
