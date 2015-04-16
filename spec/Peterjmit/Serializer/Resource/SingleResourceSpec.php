<?php

namespace spec\Peterjmit\Serializer\Resource;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Peterjmit\Serializer\Serializer;

class SingleResourceSpec extends ObjectBehavior
{
    function let(Serializer $serializer)
    {
        $this->beConstructedWith('item', $serializer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\Serializer\Resource\SingleResource');
    }

    function it_returns_the_name_from_the_serializer(Serializer $serializer)
    {
        $serializer->getName()->willReturn('book');

        $this->getKey()->shouldReturn('book');
    }

    function it_returns_the_wrapped_item()
    {
        $this->unwrap()->shouldReturn('item');
    }

    function it_serializes_the_item(Serializer $serializer)
    {
        $serializer->serialize('item')->willReturn('serialized_item');

        $this->serialize();
    }

    function it_returns_the_serializer(Serializer $serializer)
    {
        $this->getSerializer()->shouldReturn($serializer);
    }
}
