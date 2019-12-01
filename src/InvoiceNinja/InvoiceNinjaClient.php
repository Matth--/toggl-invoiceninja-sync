<?php declare(strict_types=1);

namespace Matth\Synchronizer\InvoiceNinja;

use GuzzleHttp\Client as GuzzleClient;
use Matth\Synchronizer\Dto\InvoiceNinja\Client;
use Matth\Synchronizer\Dto\InvoiceNinja\Project;
use Matth\Synchronizer\Dto\InvoiceNinja\Task;
use Symfony\Component\Serializer\SerializerInterface;

class InvoiceNinjaClient implements InvoiceNinjaClientInterface
{
    const VERSION = 'v1';

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Client constructor.
     *
     * @param GuzzleClient $client
     * @param SerializerInterface $serializer
     */
    public function __construct(GuzzleClient $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }


    public function saveNewTask(Task $task): Task
    {
        $data = $this->serializer->serialize($task, 'json');

        $res = $this->client->request('POST', self::VERSION . '/tasks', [
            'body' => $data,
        ]);

        $contents = json_decode($res->getBody()->getContents(), true);

        return $this->serializer->denormalize($contents['data'], Task::class, 'json');
    }

    public function getProjects(): array
    {
        $res = $this->client->request('GET', self::VERSION . '/projects', [
            'query' => ['per_page' => 5000],
        ]);

        $body = json_decode($res->getBody()->getContents(), true);

        return $this->serializer->denormalize($body['data'], Project::class . '[]');
    }

    public function getClients(): array
    {
        $response = $this->client->request('GET', self::VERSION . '/clients', [
            'query' => ['per_page' => 5000],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->denormalize($body['data'], Client::class . '[]');
    }
}
