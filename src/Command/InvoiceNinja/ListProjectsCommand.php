<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Command\InvoiceNinja;

use Matth\Synchronizer\InvoiceNinja\InvoiceNinjaClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Matthieu Calie <matthieu@calie.be>
 */
final class ListProjectsCommand extends Command
{
    /**
     * @var InvoiceNinjaClientInterface
     */
    private $client;

    public function __construct(InvoiceNinjaClientInterface $client)
    {
        parent::__construct('invoiceninja:list:projects');
        $this->client = $client;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $projects = $this->client->getProjects();

        $table = new Table($io);
        $table->setHeaders(['Project Name', 'Identifier']);

        foreach ($projects as $project) {
            $table->addRow([$project->getName(), $project->getId()]);
        }

        $table->render();

        return 0;
    }
}
