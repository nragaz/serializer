<?php

namespace Peterjmit\Serializer\Adapter;

use Peterjmit\Serializer\Resource\Resource;

class JsonApiAdapter implements Adapter
{
    public function serialize(Resource $resource, array $includesCollections = [])
    {
        $data = ['data' => $resource->serialize()];

        $data['linked'] = [];
        foreach ($includesCollections as $includesCollection) {
            foreach ($includesCollection->serialize() as $row) {
                $data['linked'][] = $row;
            }
        }

        return $data;
    }
}
