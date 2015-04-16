<?php

namespace Peterjmit\Serializer\Adapter;

use Peterjmit\Serializer\Resource\Resource;

class JsonApiAdapter implements Adapter
{
    public function serialize(Resource $resource, array $includesCollections = [])
    {
        $data = ['data' => $resource->serialize()];

        if (count($includesCollections) > 0) {
            $data['included'] = [];
        }

        foreach ($includesCollections as $includesCollection) {
            foreach ($includesCollection->serialize() as $row) {
                $data['included'][] = $row;
            }
        }

        return $data;
    }
}
