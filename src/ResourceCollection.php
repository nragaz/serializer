<?php

namespace Peterjmit\Serializer;

class ResourceCollection extends Resource implements Collection
{
    public function __construct(array $elements = [], Transformer $transformer)
    {
        $this->elements = $elements;
        $this->transformer = $transformer;
    }

    public function getTransformer()
    {
        return $this->transformer;
    }

    public function getKey()
    {
        return $this->transformer->getPluralKey();
    }

    public function transform()
    {
        $serialized = [];
        foreach ($this->elements as $element) {
            $serialized[] = $this->transformer->transform($element);
        }

        return $serialized;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }
}
