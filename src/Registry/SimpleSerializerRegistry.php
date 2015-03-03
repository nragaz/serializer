<?php

namespace Peterjmit\Serializer\Registry;

use Peterjmit\Serializer\Serializer;

class SimpleSerializerRegistry implements SerializerRegistry
{
    private $serializers;

    public function __construct(array $serializers = [])
    {
        foreach ($serializers as $serializer) {
            $this->register($serializer);
        }
    }

    public function register(Serializer $serializer)
    {
        $this->serializers[$serializer->getName()] = $serializer;
    }

    public function getSerializer($name)
    {
        return $this->serializers[$name];
    }

    public function resolveNestedSerializers(Serializer $serializer, array &$serializers = [], $rootName = null)
    {
        $rootName = $rootName ?: $serializer->getName();

        foreach ($serializer->getIncludes() as $name) {
            if (isset($serializers[$name]) || $name === $rootName) {
                continue;
            }

            $serializers[$name] = $this->getSerializer($name);

            return $this->resolveNestedSerializers($serializers[$name], $serializers, $rootName);
        }

        return $serializers;
    }
}
