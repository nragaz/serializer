<?php

namespace Peterjmit\Serializer;

class Resource
{
    public function __construct($object, Transformer $transformer)
    {
        $this->object = $object;
        $this->transformer = $transformer;
    }

    public function getClass()
    {
        return $this->transformer->getClass();
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getKey()
    {
        return $this->transformer->getKey();
    }

    public function transform()
    {
        return $this->transformer->transform($this->object);
    }

    public function getTransformer()
    {
        return $this->transformer;
    }
}
