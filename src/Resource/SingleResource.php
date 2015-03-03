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

    public function getName()
    {
        return $this->serializer->getName();
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
