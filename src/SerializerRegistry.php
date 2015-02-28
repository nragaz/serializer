<?php

namespace Peterjmit\Serializer;

class SerializerRegistry
{
    private $serializers;

    public function register(Serializer $serializer)
    {
        $this->serializers[$serializer->getName()] = $serializer;
    }

    public function getSerializer($name)
    {
        return $this->serializers[$name];
    }

    public function resolveNestedSerializers(Serializer $serializer, array &$serializers = [])
    {
        foreach ($serializer->getIncludes() as $name) {
            if (isset($serializers[$name])) {
                continue;
            }

            $serializers[$name] = $this->getSerializer($name);

            return $this->resolveNestedSerializers($serializers[$name], $serializers);
        }

        return $serializers;
    }

    public function createResource($item, $name)
    {
        return new Resource($item, $this->getSerializer($name));
    }

    public function createResourceCollection(array $items, $name)
    {
        return new ResourceCollection($items, $this->getSerializer($name));
    }
}
