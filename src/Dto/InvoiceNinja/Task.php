<?php declare(strict_types=1);

namespace Matth\Synchronizer\Dto\InvoiceNinja;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @author Matthieu Calie <matthieu@calie>
 */
class Task
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @SerializedName("time_log")
     *
     * @var string
     */
    private $timeLog;

    /**
     * @SerializedName("client_id")
     *
     * @var int|null
     */
    private $clientId;

    /**
     * @SerializedName("project_id")
     *
     * @var int|null
     */
    private $projectId;

    /**
     * @SerializedName("custom_value1")
     *
     * @var string|null
     */
    private $customValue1;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTimeLog(): string
    {
        return $this->timeLog;
    }

    /**
     * @param string $timeLog
     */
    public function setTimeLog(string $timeLog)
    {
        $this->timeLog = $timeLog;
    }

    /**
     * @return int
     */
    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId(int $clientId)
    {
        $this->clientId = $clientId;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function getCustomValue1(): ?string
    {
        return $this->customValue1;
    }

    public function setCustomValue1(?string $value)
    {
        $this->customValue1 = $value;
    }
}
