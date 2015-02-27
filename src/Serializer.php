<?php

namespace Peterjmit\Serializer;

interface Serializer
{
    public function serialize(Resource $resource, array $sideloadedCollections = []);
}
