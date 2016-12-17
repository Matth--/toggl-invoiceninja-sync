<?php

namespace Syncer\Dto\InvoiceNinja;

/**
 * Class Task
 * @package Syncer\Dto\InvoiceNinja
 *
 * @author Matthieu Calie <matthieu@calie>
 */
class Task
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $timeLog;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTimeLog(): string
    {
        return $this->timeLog;
    }

    /**
     * @param string $timeLog
     */
    public function setTimeLog(string $timeLog)
    {
        $this->timeLog = $timeLog;
    }
}
