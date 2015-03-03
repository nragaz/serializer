<?php

namespace Peterjmit\Serializer\Adapter;

use Peterjmit\Serializer\Resource\Resource;

interface Adapter
{
    public function serialize(Resource $resource, array $includesCollections = []);
}
