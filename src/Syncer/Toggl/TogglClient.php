<?php declare(strict_types=1);

namespace Syncer\Toggl;

use GuzzleHttp\Client as GuzzleClient;
use JMS\Serializer\SerializerInterface;
use Syncer\Dto\Toggl\Workspace;
use Syncer\Dto\Toggl\Client;
/**
 * Class TogglClient
 * @package Syncer\Toggl
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class TogglClient
{
    const VERSION = 'v8';

    /**
     * @var Client;
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $api_key;

    /**
     * TogglClient constructor.
     * @param Client $client
     * @param SerializerInterface $serializer
     * @param $api_key
     */
    public function __construct(GuzzleClient $client, SerializerInterface $serializer, $api_key)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->api_key = $api_key;
    }

    /**
     * @return array|Workspace[]
     */
    public function getWorkspaces()
    {
        $response = $this->client->request('GET', self::VERSION . '/workspaces', [
            'auth' => [$this->api_key, 'api_token'],
        ]);

        return $this->serializer->deserialize($response->getBody(), 'array<Syncer\Dto\Toggl\Workspace>', 'json');
    }

    /**
     * @return array|Client[]
     */
    public function getClientsForWorkspace($workspaceId)
    {
        $response = $this->client->request('GET', self::VERSION . '/workspaces/' . $workspaceId . '/clients', [
            'auth' => [$this->api_key, 'api_token'],
        ]);

        return $this->serializer->deserialize($response->getBody(), 'array<Syncer\Dto\Toggl\Client>', 'json');
    }
}
