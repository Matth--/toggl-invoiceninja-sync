<?php declare(strict_types=1);

namespace Syncer\Dto\Toggl;

/**
 * Class Project
 * @package Syncer\Dto\Toggl
 *
 * @author Clayton Liddell <admin@clayliddell.com>
 */
class Project
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $cid;

    /**
     * @var String
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCid(): int
    {
        return $this->cid;
    }

    /**
     * @param mixed $cid
     */
    public function setCid(int $cid)
    {
        $this->cid = $cid;
    }

    /**
     * @return mixed
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(String $name)
    {
        $this->name = $name;
    }
}
