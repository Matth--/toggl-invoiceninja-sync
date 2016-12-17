<?php

namespace Syncer\Dto\Toggl;

/**
 * Class Workspace
 * @package Syncer\Dto
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class Workspace
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
