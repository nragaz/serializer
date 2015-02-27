<?php

namespace Peterjmit\Serializer;

class TransformerRegistry
{
    public function register($class, Transformer $transformer)
    {
        $this->transformers[$class] = $transformer;
    }

    public function getTransformer($class)
    {
        return $this->transformers[$class];
    }

    public function createResource($object)
    {
        return new Resource($object, $this->getTransformer(get_class($object)));
    }

    public function createResourceCollection(array $objects)
    {
        return new ResourceCollection($objects, $this->getTransformer(get_class($objects[0])));
    }
}
