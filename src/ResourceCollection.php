<?php

namespace Peterjmit\Serializer;

class ResourceCollection implements Collection, Resource, \IteratorAggregate
{
    private $elements;
    private $serializer;

    public function __construct(array $elements = [], Serializer $serializer)
    {
        $this->elements = $elements;
        $this->serializer = $serializer;
    }

    public function getClass()
    {
        return $this->serializer->getClass();
    }

    public function getSerializer()
    {
        return $this->serializer;
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

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    public function unwrap()
    {
        return $this->elements;
    }
}
