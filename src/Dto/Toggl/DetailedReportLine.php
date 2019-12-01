<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Dto\Toggl;

final class DetailedReportLine
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $pid;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTimeInterface
     */
    private $start;

    /**
     * @var \DateTimeInterface
     */
    private $end;

    /**
     * @var int
     */
    private $dur;

    /**
     * @var string
     */
    private $client;

    /**
     * @var string
     */
    private $project;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid(int $pid): void
    {
        $this->pid = $pid;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    /**
     * @param \DateTimeInterface $start
     */
    public function setStart(\DateTimeInterface $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    /**
     * @param \DateTimeInterface $end
     */
    public function setEnd(\DateTimeInterface $end): void
    {
        $this->end = $end;
    }

    public function getDur(): int
    {
        return $this->dur;
    }

    /**
     * @param int $dur
     */
    public function setDur(int $dur): void
    {
        $this->dur = $dur;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    /**
     * @param string $client
     */
    public function setClient(string $client): void
    {
        $this->client = $client;
    }

    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * @param string $project
     */
    public function setProject(string $project): void
    {
        $this->project = $project;
    }
}
