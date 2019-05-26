<?php declare(strict_types=1);

namespace Syncer\Dto\InvoiceNinja;

/**
 * Class ClientsWrapper
 * @package Syncer\Dto\InvoiceNinja
 *
 * @author Clayton Liddell <admin@clayliddell.com>
 */
class ClientsWrapper
{
    /**
     * @var array<Syncer\Dto\InvoiceNinja\Client>
     */
    private $data;

    /**
     * @return array<Syncer\Dto\InvoiceNinja\Client>
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array<Syncer\Dto\InvoiceNinja\Client> $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
