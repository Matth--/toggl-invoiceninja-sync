<?php

namespace spec\Syncer\Toggl;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Syncer\Toggl\TogglClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TogglClientSpec extends ObjectBehavior
{
    function let(Client $client, SerializerInterface $serializer)
    {
        $this->beConstructedWith($client, $serializer, 'key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TogglClient::class);
    }

    function it_can_get_all_workspaces(Client $client, SerializerInterface $serializer, ResponseInterface $response)
    {
        $response->getBody()->shouldBeCalled()->willReturn('body');

        $client->request('GET', 'v8/workspaces', Argument::any())->shouldBeCalled()->willReturn($response);

        $serializer->deserialize('body', 'array<Syncer\Dto\Toggl\Workspace>', 'json')->shouldBeCalled();

        $this->getWorkspaces();
    }
}
