<?php

namespace spec\Syncer\Toggl;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Syncer\Dto\Toggl\DetailedReport;
use Syncer\Toggl\ReportsClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReportsClientSpec extends ObjectBehavior
{
    function let(Client $client, SerializerInterface $serializer)
    {
        $this->beConstructedWith($client, $serializer, 'key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ReportsClient::class);
    }

    function it_can_get_a_detailed_report(Client $client, SerializerInterface $serializer, ResponseInterface $response)
    {
        $response->getBody()->shouldBeCalled()->willReturn('body');

        $client->request('GET', 'v2/details', Argument::any())->shouldBeCalled()->willReturn($response);

        $serializer->deserialize('body', DetailedReport::class, 'json')->shouldBeCalled();

        $this->getDetailedReport(1);
    }
}
