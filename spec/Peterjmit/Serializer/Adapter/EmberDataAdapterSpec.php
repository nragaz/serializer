<?php

namespace spec\Peterjmit\Serializer\Adapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Peterjmit\Serializer\Resource\Resource;

class EmberDataAdapterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\Serializer\Adapter\EmberDataAdapter');
    }

    function it_serializes_a_resource_with_its_key_as_the_top_level_key(
        Resource $resource
    ) {
        $resource->getKey()->willReturn('example');
        $resource->serialize()->willReturn(['id' => 1, 'name' => 'test']);

        $this->serialize($resource, [])
            ->shouldReturn(['example' => ['id' => 1, 'name' => 'test']]);
    }

    function it_serializes_an_included_resource_with_its_key_as_a_top_level_key(
        Resource $resource,
        Resource $includedResource
    ) {
        $resource->getKey()->willReturn('example');
        $resource->serialize()->willReturn(['id' => 1, 'name' => 'test']);

        $includedResource->getKey()->willReturn('included_resource');
        $includedResource->serialize()->willReturn([['id' => 2, 'name' => 'test_2']]);

        $this->serialize($resource, [$includedResource])
            ->shouldReturn([
                'example' => ['id' => 1, 'name' => 'test'],
                'included_resource' => [
                    ['id' => 2, 'name' => 'test_2']
                ]
            ]);
    }
}
