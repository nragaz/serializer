<?php

namespace spec\Peterjmit\Serializer\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Peterjmit\Serializer\Serializer;

class ResourceCollectionSpec extends ObjectBehavior
{
    function let(Serializer $serializer)
    {
        $this->beConstructedWith(['item'], $serializer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\Serializer\Resource\ResourceCollection');
    }

    function it_is_an_iterator_aggregate()
    {
        $this->shouldImplement('IteratorAggregate');
    }

    function it_returns_the_plural_key_from_the_serializer(Serializer $serializer)
    {
        $serializer->getPluralKey()->willReturn('books');

        $this->getKey()->shouldReturn('books');
    }

    function it_returns_the_wrapped_array()
    {
        $this->unwrap()->shouldReturn(['item']);
    }

    function it_serializes_the_wrapped_array(Serializer $serializer)
    {
        $serializer->serialize('item')->willReturn('serialized_item');

        $this->serialize()->shouldReturn(['serialized_item']);
    }

    function it_returns_the_serializer(Serializer $serializer)
    {
        $this->getSerializer()->shouldReturn($serializer);
    }

    function it_returns_an_array_iterator()
    {
        $this->getIterator()->shouldReturnAnInstanceOf(\ArrayIterator::class);
    }
}
