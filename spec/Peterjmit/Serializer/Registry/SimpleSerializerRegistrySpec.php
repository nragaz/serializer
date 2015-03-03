<?php

namespace spec\Peterjmit\Serializer\Registry;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Peterjmit\Serializer\Serializer;

class SimpleSerializerRegistrySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\Serializer\Registry\SimpleSerializerRegistry');
    }

    function it_registers_a_serializer(Serializer $serializer)
    {
        $serializer->getName()->willReturn('book');

        $this->register($serializer);

        $this->getSerializer('book')->shouldReturn($serializer);
    }

    function it_can_be_constructed_with_serializers(Serializer $serializer)
    {
        $serializer->getName()->willReturn('book');

        $this->beConstructedWith([$serializer]);

        $this->getSerializer('book')->shouldReturn($serializer);
    }

    function it_finds_a_nested_serializer_for_a_given_serializer(
        Serializer $bookSerializer,
        Serializer $authorSerializer
    ) {
        $bookSerializer->getName()->willReturn('book');
        $this->register($bookSerializer);

        $authorSerializer->getName()->willReturn('author');
        $this->register($authorSerializer);

        $bookSerializer->getIncludes()->willReturn(['author']);
        $authorSerializer->getIncludes()->willReturn([]);

        $this->resolveNestedSerializers($bookSerializer)
            ->shouldReturn(['author' => $authorSerializer]);
    }

    function it_finds_nested_serializers_2_levels_deep(
        Serializer $bookSerializer,
        Serializer $authorSerializer,
        Serializer $publisherSerializer
    ) {
        $bookSerializer->getName()->willReturn('book');
        $this->register($bookSerializer);

        $authorSerializer->getName()->willReturn('author');
        $this->register($authorSerializer);

        $publisherSerializer->getName()->willReturn('publisher');
        $this->register($publisherSerializer);

        $bookSerializer->getIncludes()->willReturn(['author']);
        $authorSerializer->getIncludes()->willReturn(['publisher']);
        $publisherSerializer->getIncludes()->willReturn([]);

        $this->resolveNestedSerializers($bookSerializer)
            ->shouldReturn([
                'author' => $authorSerializer,
                'publisher' => $publisherSerializer,
            ]);
    }

    function it_does_not_explode_when_finding_nested_serializers_with_circular_references(
        Serializer $bookSerializer,
        Serializer $authorSerializer
    ) {
        $bookSerializer->getName()->willReturn('book');
        $this->register($bookSerializer);

        $authorSerializer->getName()->willReturn('author');
        $this->register($authorSerializer);

        $bookSerializer->getIncludes()->willReturn(['author']);
        $authorSerializer->getIncludes()->willReturn(['book']);

        $this->resolveNestedSerializers($bookSerializer)
            ->shouldContain($authorSerializer);
    }

    function it_does_not_include_the_provided_serializer_when_finding_nested_serializers(
        Serializer $bookSerializer,
        Serializer $authorSerializer
    ) {
        $bookSerializer->getName()->willReturn('book');
        $this->register($bookSerializer);

        $authorSerializer->getName()->willReturn('author');
        $this->register($authorSerializer);

        $bookSerializer->getIncludes()->willReturn(['author']);
        $authorSerializer->getIncludes()->willReturn(['book']);

        $this->resolveNestedSerializers($bookSerializer)
            ->shouldNotContain($bookSerializer);
    }
}
