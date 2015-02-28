<?php

namespace Peterjmit\Serializer\Adapter;

use Peterjmit\Serializer\Resource;

interface Adapter
{
    public function serialize(Resource $resource, array $includesCollections = []);
}
