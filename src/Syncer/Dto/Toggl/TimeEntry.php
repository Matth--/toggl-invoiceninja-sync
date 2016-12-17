<?php

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
    private $billable;

    /**
     * @var boolean
     */
    private $isBillable;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return int
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * @param int $tid
     */
    public function setTid($tid)
    {
        $this->tid = $tid;
    }

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function isUseStop()
    {
        return $this->useStop;
    }

    /**
     * @param bool $useStop
     */
    public function setUseStop($useStop)
    {
        $this->useStop = $useStop;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param string $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getProjectColor()
    {
        return $this->projectColor;
    }

    /**
     * @param string $projectColor
     */
    public function setProjectColor($projectColor)
    {
        $this->projectColor = $projectColor;
    }

    /**
     * @return string
     */
    public function getProjectHexColor()
    {
        return $this->projectHexColor;
    }

    /**
     * @param string $projectHexColor
     */
    public function setProjectHexColor($projectHexColor)
    {
        $this->projectHexColor = $projectHexColor;
    }

    /**
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param string $task
     */
    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @return string
     */
    public function getBillable()
    {
        return $this->billable;
    }

    /**
     * @param string $billable
     */
    public function setBillable($billable)
    {
        $this->billable = $billable;
    }

    /**
     * @return bool
     */
    public function isIsBillable()
    {
        return $this->isBillable;
    }

    /**
     * @param bool $isBillable
     */
    public function setIsBillable($isBillable)
    {
        $this->isBillable = $isBillable;
    }

    /**
     * @return string
     */
    public function getCur()
    {
        return $this->cur;
    }

    /**
     * @param string $cur
     */
    public function setCur($cur)
    {
        $this->cur = $cur;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
}
