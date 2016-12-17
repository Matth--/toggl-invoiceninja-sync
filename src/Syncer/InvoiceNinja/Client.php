<?php

namespace Syncer\InvoiceNinja;

use GuzzleHttp\Client as GuzzleClient;
use JMS\Serializer\SerializerInterface;
use Syncer\Dto\InvoiceNinja\Task;

/**
 * Class Client
 * @package Syncer\InvoiceNinja
 *
 * @author Matthieu Calie <matthieu@calie.be>
 */
class Client
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
     * @var string
     */
    private $api_token;

    /**
     * Client constructor.
     *
     * @param GuzzleClient $client
     * @param SerializerInterface $serializer
     * @param $api_token
     */
    public function __construct(GuzzleClient $client, SerializerInterface $serializer, $api_token)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->api_token = $api_token;
    }

    /**
     * @param Task $task
     *
     * @return mixed
     */
    public function saveNewTask(Task $task)
    {
        $data = $this->serializer->serialize($task, 'json');

        $res = $this->client->request('POST', self::VERSION . '/tasks', [
            'body' => $data,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Ninja-Token' => $this->api_token,
                'X-Requested-With' => 'XMLHttpRequest',
            ]
        ]);

        return json_decode($res->getBody());
    }
}
