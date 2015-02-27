<?php

namespace Peterjmit\Serializer;

class JsonApiSerializer implements Serializer
{
    public function serialize(Resource $resource, array $sideloadedCollections = [])
    {
        $data['data'] = $resource->transform();

        $data['linked'] = [];
        foreach ($sideloadedCollections as $sideloadedCollection) {
            foreach ($sideloadedCollection->transform() as $row) {
                $data['linked'][] = $row;
            }
        }

        return $data;
    }
}
