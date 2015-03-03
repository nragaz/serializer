<?php

namespace Peterjmit\Serializer\Registry;

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


}
