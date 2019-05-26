<?php declare(strict_types=1);

namespace Syncer\Dto\InvoiceNinja;

/**
 * Class ProjectWrapper
 * @package Syncer\Dto\InvoiceNinja
 *
 * @author Clayton Liddell <admin@clayliddell.com>
 */
class ProjectWrapper
{
    /**
     * @var Syncer\Dto\InvoiceNinja\Project
     */
    private $data;

    /**
     * @return Syncer\Dto\InvoiceNinja\Project
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param Syncer\Dto\InvoiceNinja\Project $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
