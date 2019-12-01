<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Command\InvoiceNinja;

use Matth\Synchronizer\InvoiceNinja\InvoiceNinjaClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListClientsCommand extends Command
{
    /**
     * @var InvoiceNinjaClientInterface
     */
    private $client;

    public function __construct(InvoiceNinjaClientInterface $client)
    {
        parent::__construct('invoiceninja:list:clients');

        $this->client = $client;
    }

    protected function configure()
    {
        $this->setDescription('List the invoiceninja projects');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $clients = $this->client->getClients();

        $table = new Table($io);
        $table->setHeaders(['Client Name', 'Identifier']);

        foreach ($clients as $client) {
            $table->addRow([$client->getName(), $client->getId()]);
        }

        $table->render();

        return 0;
    }
}
