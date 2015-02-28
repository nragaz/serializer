<?php

namespace Peterjmit\Serializer;

class IncludesCollector
{
    private $registry;
    private $mainResourceClass;

    public function __construct(SerializerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function collect(Resource $resource)
    {
        $this->mainResourceClass = $resource->getClass();

        $serializer = $resource->getSerializer();
        $nestedSerializers = $this->registry->resolveNestedSerializers($serializer);

        if (isset($nestedSerializers[$this->mainResourceClass])) {
            unset($nestedSerializers[$this->mainResourceClass]);
        }

        $collections = [];
        foreach ($nestedSerializers as $class => $nestedSerializer) {
            $collections[$class] = new ProcessingCollection([], $nestedSerializer);
        }

        // Load direct children of the resource into collections
        if ($resource instanceof Collection) {
            foreach ($resource as $item) {
                $this->collectDirtyObjects($serializer, $item, $collections);
            }
        } else {
            $this->collectDirtyObjects($serializer, $resource->unwrap(), $collections);
        }

        // Recursively load the tree of nested objects into the collections
        $this->processCollections($collections);

        // Return ResourceCollection[] instead of
        // instances of ProcessingCollection
        return array_map(function (ProcessingCollection $collection) {
            return $collection->toResourceCollection();
        }, $collections);
    }

    private function collectDirtyObjects(Serializer $serializer, $item, &$collections)
    {
        foreach ($serializer->getIncludes() as $class) {
            if ($class === $this->mainResourceClass) {
                continue;
            }

            if ($object = $serializer->collectIncludes($item, $class)) {
                $collections[$class]->addUnprocessed($object);
            }
        }
    }

    private function processCollections(&$collections)
    {
        foreach ($collections as $collection) {
            $serializer = $collection->getSerializer();
            foreach ($collection->getUnprocessed() as $object) {
                $collection->process($object);
                $this->collectDirtyObjects($serializer, $object, $collections);
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
