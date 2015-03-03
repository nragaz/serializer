<?php

namespace Peterjmit\Serializer\Registry;

use Peterjmit\Serializer\Serializer;

interface SerializerRegistry
{
    public function register(Serializer $serializer);

    public function getSerializer($name);

    public function resolveNestedSerializers(Serializer $serializer, array $serializers);
}
