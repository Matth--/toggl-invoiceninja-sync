<?php

declare(strict_types=1);

namespace Matth\Synchronizer\Dto\InvoiceNinja;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class Project
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $clientId;

    /**
     * Project constructor.
     * @param int $id
     * @param string $name
     * @param int $client_id
     */
    public function __construct(int $id, string $name, int $client_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->clientId = $client_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }
}
