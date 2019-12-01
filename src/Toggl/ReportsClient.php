<?php declare(strict_types=1);

namespace Matth\Synchronizer\Toggl;

use Carbon\Carbon;
use GuzzleHttp\ClientInterface;
use Matth\Synchronizer\Dto\Toggl\DetailedReport;
use Symfony\Component\Serializer\SerializerInterface;

class ReportsClient implements ReportsClientInterface
{
    const VERSION = 'v2';

    /**
     * @var ClientInterface;
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * TogglClient constructor.
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     */
    public function __construct(ClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function getDetailedReport(int $workspaceId): DetailedReport
    {
        $res = $this->client->request('GET', self::VERSION . '/details', [
            'auth' => ['e62692334be737f241c9fd7c923817a4', 'api_token'],
            'query' => [
                'user_agent' => 'matthieu@calie.be',
                'workspace_id' => $workspaceId,
                'since' => Carbon::yesterday()->format('Y-m-d')
            ]
        ]);

//        dump($res->getBody()->getContents());die;

        return $this->serializer->deserialize($res->getBody(), DetailedReport::class, 'json');
    }
}
