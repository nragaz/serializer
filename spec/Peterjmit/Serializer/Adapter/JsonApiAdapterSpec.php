<?php

namespace spec\Peterjmit\Serializer\Adapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Peterjmit\Serializer\Resource\Resource;

class JsonApiAdapterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\Serializer\Adapter\JsonApiAdapter');
    }

    function it_serializes_a_resource_with_data_the_top_level_key(
        Resource $resource
    ) {
        $resource->serialize()->willReturn(['id' => 1, 'name' => 'test']);

        $this->serialize($resource, [])
            ->shouldReturn(['data' => ['id' => 1, 'name' => 'test']]);
    }

    function it_serializes_an_included_resource_under_the_included_namespace(
        Resource $resource,
        Resource $includedResource
    ) {
        $resource->serialize()->willReturn(['id' => 1, 'name' => 'test']);

        $includedResource->serialize()->willReturn([['id' => 2, 'name' => 'test_2']]);

        $this->serialize($resource, [$includedResource])
            ->shouldReturn([
                'data' => ['id' => 1, 'name' => 'test'],
                'included' => [
                    ['id' => 2, 'name' => 'test_2']
                ]
            ]);
    }
}
