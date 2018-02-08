<?php declare(strict_types=1);

namespace Syncer\Toggl;

use Carbon\Carbon;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Syncer\Dto\Toggl\DetailedReport;

/**
 * Class ReportsClient
 * @package Syncer\Toggl
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class ReportsClient
{
    const VERSION = 'v2';

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
    public function __construct(Client $client, SerializerInterface $serializer, $api_key)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->api_key = $api_key;
    }

    /**
     * Get detailed report from since yesterday
     *
     * @param int $workspaceId
     * @return array|\JMS\Serializer\scalar|object|DetailedReport
     */
    public function getDetailedReport(int $workspaceId)
    {
        $res = $this->client->request('GET', self::VERSION . '/details', [
            'auth' => [$this->api_key, 'api_token'],
            'query' => [
                'user_agent' => 'matthieu@calie.be',
                'workspace_id' => $workspaceId,
                'since' => Carbon::yesterday()->format('Y-m-d')
            ]
        ]);

        return $this->serializer->deserialize($res->getBody(), DetailedReport::class, 'json');
    }
}
