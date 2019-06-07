<?php declare(strict_types=1);

namespace Syncer\Dto\Toggl;

/**
 * Class TimeEntry
 * @package Syncer\Dto\Toggl
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class TimeEntry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $pid;

    /**
     * @var integer
     */
    private $tid;

    /**
     * @var integer
     */
    private $uid;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var string
     */
    private $user;

    /**
     * @var boolean
     */
    private $useStop;

    /**
     * @var string
     */
    private $client;

    /**
     * @var string
     */
    private $project;

    /**
     * @var string
     */
    private $projectColor;

    /**
     * @var string
     */
    private $projectHexColor;

    /**
     * @var string
     */
    private $task;

    /**
     * @var string
     */
    private $billableText;

    /**
     * @var boolean
     */
    private $billable;

    /**
     * @var string
     */
    private $cur;

    /**
     * @var array
     */
    private $tags;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid(int $pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return int
     */
    public function getTid(): int
    {
        return $this->tid;
    }

    /**
     * @param int $tid
     */
    public function setTid(int $tid)
    {
        $this->tid = $tid;
    }

    /**
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid)
    {
        $this->uid = $uid;
    }

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
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function isUseStop(): bool
    {
        return $this->useStop;
    }

    /**
     * @param bool $useStop
     */
    public function setUseStop(bool $useStop)
    {
        $this->useStop = $useStop;
    }

    /**
     * @return string
     */
    public function getClient(): ?string
    {
        return $this->client;
    }

    /**
     * @param string $client
     */
    public function setClient(?string $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getProject(): ?string
    {
        return $this->project;
    }

    /**
     * @param string $project
     */
    public function setProject(?string $project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getProjectColor(): string
    {
        return $this->projectColor;
    }

    /**
     * @param string $projectColor
     */
    public function setProjectColor(string $projectColor)
    {
        $this->projectColor = $projectColor;
    }

    /**
     * @return string
     */
    public function getProjectHexColor(): string
    {
        return $this->projectHexColor;
    }

    /**
     * @param string $projectHexColor
     */
    public function setProjectHexColor(string $projectHexColor)
    {
        $this->projectHexColor = $projectHexColor;
    }

    /**
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * @param string $task
     */
    public function setTask(string $task)
    {
        $this->task = $task;
    }

    /**
     * @return string
     */
    public function getBillableText(): string
    {
        return $this->billableText;
    }

    /**
     * @param string $billableText
     */
    public function setBillableText(string $billableText)
    {
        $this->billableText = $billableText;
    }

    /**
     * @return bool
     */
    public function isBillable(): bool
    {
        return $this->billable;
    }

    /**
     * @param bool $billable
     */
    public function setBillable(bool $billable)
    {
        $this->billable = $billable;
    }

    /**
     * @return string
     */
    public function getCur(): string
    {
        return $this->cur;
    }

    /**
     * @param string $cur
     */
    public function setCur(string $cur)
    {
        $this->cur = $cur;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }
}
