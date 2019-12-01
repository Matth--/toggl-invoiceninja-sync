<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Command\Toggl;

use Matth\Synchronizer\Toggl\TogglClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListProjectsCommand extends Command
{
    /**
     * @var TogglClientInterface
     */
    private $togglClient;

    public function __construct(TogglClientInterface $togglClient)
    {
        parent::__construct('toggl:list:projects');
        $this->togglClient = $togglClient;
    }

    protected function configure()
    {
        $this
            ->setDescription('List Toggl projects')
            ->addOption('workspace_id', 'w', InputOption::VALUE_OPTIONAL, 'Filter on workspace identifier')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (!$workspaceId = (int) $input->getOption('workspace_id')) {
            $workspaces = $this->togglClient->getWorkSpaces();

            if (count($workspaces) === 1) {
                $workspaceId = $workspaces[0]->getId();
            }

            if (count($workspaces) > 1) {
                $choices = [];

                foreach ($workspaces as $workspace) {
                    $choices[] = $workspace->getName() . ' - ' . $workspace->getId();
                }

                $question = new ChoiceQuestion('List projects from what workspace?', $choices);

                $workspaceId = (int) explode(' - ', $io->askQuestion($question))[1];
            }

            if (!$workspaceId) {
                $io->error('Could not find a valid workspace');
                return 1;
            }
        }

        $projects = $this->togglClient->getProjectsByWorkSpace($workspaceId);

        $table = new Table($io);
        $table->setHeaders(['Project Name', 'Identifier']);

        foreach ($projects as $project) {
            $table->addRow([$project->getName(), $project->getId()]);
        }

        $table->render();

        return 0;
    }
}
