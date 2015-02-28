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

    public function resolveNestedSerializers(Serializer $serializer, array &$serializers = [])
    {
        foreach ($serializer->getIncludes() as $class) {
            if (isset($serializers[$class])) {
                continue;
            }

            $serializers[$class] = $this->getSerializer($class);

            return $this->resolveNestedSerializers($serializers[$class], $serializers);
        }

        return $serializers;
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
