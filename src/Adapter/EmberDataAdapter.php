<?php

namespace Peterjmit\Serializer\Adapter;

use Peterjmit\Serializer\Resource\Resource;

class EmberDataAdapter implements Adapter
{
    public function serialize(Resource $resource, array $includesCollections = [])
    {
        $data = [$resource->getKey() => $resource->serialize()];

        foreach ($includesCollections as $includesCollection) {
            $data[$includesCollection->getKey()] = $includesCollection->serialize();
        }

        return $data;
    }
}
