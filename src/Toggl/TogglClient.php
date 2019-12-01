<?php declare(strict_types=1);

namespace Matth\Synchronizer\Toggl;

use GuzzleHttp\ClientInterface;
use Matth\Synchronizer\Dto\Toggl\Client;
use Matth\Synchronizer\Dto\Toggl\Project;
use Symfony\Component\Serializer\SerializerInterface;
use Matth\Synchronizer\Dto\Toggl\Workspace;

/**
 * @author Matthieu Calie <matthieu@calie.be>
 */
class TogglClient implements TogglClientInterface
{
    const VERSION = 'v8';

    /**
     * @var ClientInterface;
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     */
    public function __construct(ClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function getWorkspaces(): array
    {
        $response = $this->client->request('GET', self::VERSION . '/workspaces');

        return $this->serializer->deserialize($response->getBody(), Workspace::class . '[]', 'json');
    }

    public function getProjectsByWorkSpace(int $workspaceId): array
    {
        $response = $this->client->request('GET', sprintf('%s/workspaces/%d/projects', self::VERSION, $workspaceId));

        if (($contents = $response->getBody()->getContents()) === "null") {
            return [];
        }

        return $this->serializer->deserialize($contents, Project::class . '[]', 'json');
    }

    public function getClientsByWorkspaceId(int $workspaceId): array
    {
        $response = $this->client->request('GET', sprintf('%s/workspaces/%d/clients', self::VERSION, $workspaceId));

        if (($contents = $response->getBody()->getContents()) === "null") {
            return [];
        }

        return $this->serializer->deserialize($contents, Client::class . '[]', 'json');
    }
}
