<?php

namespace Peterjmit\Serializer\IncludeMapping;

use Peterjmit\Serializer\IncludeMapping\ProcessingCollections;
use Peterjmit\Serializer\Resource\ResourceCollection;
use Peterjmit\Serializer\Resource\Resource;
use Peterjmit\Serializer\SerializerRegistry;

class IncludesCollector
{
    private $registry;

    public function __construct(SerializerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function collect(Resource $resource)
    {
        $serializer = $resource->getSerializer();

        $collections = new ProcessingCollections(
            $serializer->getName(),
            $this->registry->resolveNestedSerializers($serializer)
        );

        // Load direct children of the resource into collections
        if ($resource instanceof ResourceCollection) {
            foreach ($resource as $item) {
                $collections->process($serializer, $item);
            }
        } else {
            $collections->process($serializer, $resource->unwrap());
        }

        $collections->processNested();

        return $collections->toResourceCollections();
    }
}
