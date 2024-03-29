<?php

namespace Peterjmit\Serializer\Resource;

use Peterjmit\Serializer\Serializer;

class SingleResource implements Resource
{
    private $item;
    private $serializer;

    public function __construct($item, Serializer $serializer)
    {
        $this->item = $item;
        $this->serializer = $serializer;
    }

    public function getKey()
    {
        return $this->serializer->getName();
    }

    public function serialize($append = false)
    {
        return $this->serializer->serialize($this->item);
    }

    public function unwrap()
    {
        return $this->item;
    }

    public function getSerializer()
    {
        return $this->serializer;
    }
}
