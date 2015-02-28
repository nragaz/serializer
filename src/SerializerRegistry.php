<?php

namespace Peterjmit\Serializer;

class SerializerRegistry
{
    private $serializers;

    public function register(Serializer $serializer)
    {
        $this->serializers[$serializer->getClass()] = $serializer;
    }

    public function getSerializer($class)
    {
        return $this->serializers[$class];
    }

    public function createResource($object)
    {
        return new Resource($object, $this->getSerializer(get_class($object)));
    }

    public function createResourceCollection(array $objects, $class = null)
    {
        return new ResourceCollection($objects, $this->getSerializer($class ?: get_class($objects[0])));
    }
}
