<?php

namespace spec\Syncer\InvoiceNinja;

use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Syncer\Dto\InvoiceNinja\Task;
use Syncer\InvoiceNinja\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let(\GuzzleHttp\Client $client, SerializerInterface $serializer)
    {
        $this->beConstructedWith($client, $serializer, 'token');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    function it_saves_a_new_task(\GuzzleHttp\Client $client, SerializerInterface $serializer, ResponseInterface $response)
    {
        $task = new Task();
        $response->getBody()->shouldBeCalled()->willReturn('body');

        $serializer->serialize($task, 'json')->shouldBeCalled();
        $client->request('POST', 'v1/tasks', Argument::any())->shouldBeCalled()->willReturn($response);
        $serializer->deserialize('body', Task::class, 'json')->shouldBeCalled()->willReturn('body');

        $this->saveNewTask($task)->shouldReturn('body');

    }
}
