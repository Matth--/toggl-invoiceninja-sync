<?php declare(strict_types=1);

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
}
