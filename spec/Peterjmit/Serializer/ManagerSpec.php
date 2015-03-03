<?php

namespace spec\Peterjmit\Serializer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Peterjmit\Serializer\Adapter\Adapter;
use Peterjmit\Serializer\IncludeMapping\IncludesCollector;
use Peterjmit\Serializer\Registry\SerializerRegistry;
use Peterjmit\Serializer\Resource\Resource;
use Peterjmit\Serializer\Resource\ResourceCollection;
use Peterjmit\Serializer\Resource\SingleResource;
use Peterjmit\Serializer\Serializer;

class ManagerSpec extends ObjectBehavior
{
    public function let(
        SerializerRegistry $registry,
        IncludesCollector $collector,
        Adapter $adapter
    ) {
        $this->beConstructedWith($registry, $collector, $adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\Serializer\Manager');
    }

    function it_creates_a_resource_from_an_item(
        SerializerRegistry $registry,
        Serializer $serializer
    ) {
        $registry->getSerializer('name')->willReturn($serializer);

        $this->createResource(new \stdClass, 'name')
            ->shouldReturnAnInstanceOf(SingleResource::class);
    }

    function it_creates_a_resource_collection_from_an_array_of_items(
        SerializerRegistry $registry,
        Serializer $serializer
    ) {
        $registry->getSerializer('name')->willReturn($serializer);

        $this->createResourceCollection([new \stdClass, new \stdClass], 'name')
            ->shouldReturnAnInstanceOf(ResourceCollection::class);
    }

    function it_serializes_a_resource_with_sideloaded_items(
        Resource $resource,
        IncludesCollector $collector,
        Adapter $adapter
    ) {
        $collector->collect($resource)->willReturn([]);

        $adapter->serialize($resource, [])
            ->willReturn(['serialized' => ['data']]);

        $this->serialize($resource)
            ->shouldReturn(['serialized' => ['data']]);
    }
}
