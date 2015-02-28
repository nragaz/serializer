<?php

namespace Peterjmit\Serializer;

class SingleResource implements Resource
{
    private $item;
    private $serializer;

    public function __construct($item, Serializer $serializer)
    {
        $this->item = $item;
        $this->serializer = $serializer;
    }

    public function getClass()
    {
        return $this->serializer->getClass();
    }

    public function unwrap()
    {
        return $this->item;
    }

    public function getKey()
    {
        return $this->serializer->getKey();
    }

    public function serialize()
    {
        return $this->serializer->serialize($this->item);
    }

    public function getSerializer()
    {
        return $this->serializer;
    }
}
