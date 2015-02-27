<?php

namespace Peterjmit\Serializer;

class EmberDataSerializer implements Serializer
{
    public function serialize(Resource $resource, array $sideloadedCollections = [])
    {
        $data[$resource->getKey()] = $resource->transform();

        foreach ($sideloadedCollections as $sideloadedCollection) {
            $data[$sideloadedCollection->getKey()] = $sideloadedCollection->transform();
        }

        return $data;
    }
}
