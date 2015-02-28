<?php

namespace Peterjmit\Serializer;

class IncludesCollector
{
    private $registry;
    private $mainResourceName;

    public function __construct(SerializerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function collect(Resource $resource)
    {
        $this->mainResourceName = $resource->getName();

        $serializer = $resource->getSerializer();
        $nestedSerializers = $this->registry->resolveNestedSerializers($serializer);

        if (isset($nestedSerializers[$this->mainResourceName])) {
            unset($nestedSerializers[$this->mainResourceName]);
        }

        $collections = [];
        foreach ($nestedSerializers as $name => $nestedSerializer) {
            $collections[$name] = new ProcessingCollection([], $nestedSerializer);
        }

        // Load direct children of the resource into collections
        if ($resource instanceof Collection) {
            foreach ($resource as $item) {
                $this->collectDirtyItems($serializer, $item, $collections);
            }
        } else {
            $this->collectDirtyItems($serializer, $resource->unwrap(), $collections);
        }

        // Recursively load the tree of nested objects into the collections
        $this->processCollections($collections);

        $resourceCollections = [];
        foreach($collections as $collection) {
            if ($collection->countProcessed() > 0) {
                $resourceCollections[] = $collection->toResourceCollection();
            }
        }

        return $resourceCollections;
    }

    private function collectDirtyItems(Serializer $serializer, $item, &$collections)
    {
        foreach ($serializer->getIncludes() as $name) {
            if ($name === $this->mainResourceName) {
                continue;
            }

            if ($item = $serializer->collectIncludes($item, $name)) {
                $collections[$name]->addUnprocessed($item);
            }
        }
    }

    private function processCollections(&$collections)
    {
        foreach ($collections as $collection) {
            $serializer = $collection->getSerializer();
            foreach ($collection->getUnprocessed() as $item) {
                $collection->process($item);
                $this->collectDirtyItems($serializer, $item, $collections);
            }
        }

        $remaining = 0;
        foreach ($collections as $collection) {
            $remaining += $collection->countUnprocessed();
        }

        if ($remaining > 0) {
            $this->processCollections();
        }
    }
}
