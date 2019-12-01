<?php

declare(strict_types=1);

namespace Matth\Synchronizer\InvoiceNinja;

use Matth\Synchronizer\Dto\InvoiceNinja\Client;
use Matth\Synchronizer\Dto\InvoiceNinja\Project;
use Matth\Synchronizer\Dto\InvoiceNinja\Task;

interface InvoiceNinjaClientInterface
{
    /**
     * @param Task $task
     *
     * @return Task
     */
    public function saveNewTask(Task $task): Task;

    /**
     * @return Project[]
     */
    public function getProjects(): array;

    /**
     * @return Client[]
     */
    public function getClients(): array;
}
