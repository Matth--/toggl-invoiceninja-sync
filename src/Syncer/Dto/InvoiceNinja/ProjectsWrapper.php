<?php declare(strict_types=1);

namespace Syncer\Dto\InvoiceNinja;

/**
 * Class ProjectsWrapper
 * @package Syncer\Dto\InvoiceNinja
 *
 * @author Clayton Liddell <admin@clayliddell.com>
 */
class ProjectsWrapper
{
    /**
     * @var array<Syncer\Dto\InvoiceNinja\Project>
     */
    private $data;

    /**
     * @return array<Syncer\Dto\InvoiceNinja\Project>
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array<Syncer\Dto\InvoiceNinja\Project> $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
