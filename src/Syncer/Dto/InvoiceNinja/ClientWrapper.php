<?php declare(strict_types=1);

namespace Syncer\Dto\InvoiceNinja;

/**
 * Class ClientWrapper
 * @package Syncer\Dto\InvoiceNinja
 *
 * @author Clayton Liddell <admin@clayliddell.com>
 */
class ClientWrapper
{
    /**
     * @var Syncer\Dto\InvoiceNinja\Client
     */
    private $data;

    /**
     * @return Syncer\Dto\InvoiceNinja\Client
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param Syncer\Dto\InvoiceNinja\Client $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
