<?php

namespace Peterjmit\Serializer\Resource;

use Peterjmit\Serializer\Serializer;

class ResourceCollection implements Resource, \IteratorAggregate
{
    private $elements;
    private $serializer;

    public function __construct(array $elements, Serializer $serializer)
    {
        $this->elements = $elements;
        $this->serializer = $serializer;
    }

    public function getKey()
    {
        return $this->serializer->getPluralKey();
    }

    public function serialize()
    {
        $serialized = [];
        foreach ($this->elements as $element) {
            $serialized[] = $this->serializer->serialize($element);
        }

        return $serialized;
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

    public function unwrap()
    {
        return $this->elements;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }
}
