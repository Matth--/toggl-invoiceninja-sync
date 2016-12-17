<?php

/*
 * This file is property of Wijs.be
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

namespace Syncer\Dto;

/**
 * Class Workspace
 * @package Syncer\Dto
 *
 * @author Matthieu Calie <matthieu.calie@wijs.be>
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
