<?php

namespace Peterjmit\Serializer;

class IncludesCollector
{
    private $registry;
    private $sideloadCollections;
    private $mainResourceClass;

    public function __construct(SerializerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function collect(Resource $resource)
    {
        $this->mainResourceClass = $resource->getClass();
        $this->sideloadCollections = [];

        $serializer = $resource->getSerializer();

        // Recursively find all sideloading required for this object and nested
        // objects. Initialize collections for those objects
        $this->initializeNestedCollections($serializer);

        // Load direct children of the resource into collections
        if ($resource instanceof Collection) {
            foreach ($resource as $item) {
                $this->collectDirtyObjects($serializer, $item);
            }
        } else {
            $this->collectDirtyObjects($serializer, $resource->getObject());
        }

        // Recursively load the tree of nested objects into the collections
        $this->processCollections();

        // Return ResourceCollection[] instead of
        // instances of ProcessingCollection
        return array_map(function (ProcessingCollection $collection) {
            return $collection->toResourceCollection();
        }, $this->sideloadCollections);
    }

    private function initializeNestedCollections(Serializer $serializer)
    {
        foreach ($serializer->getIncludes() as $class) {
            if ($class === $this->mainResourceClass || isset($this->sideloadCollections[$class])) {
                continue;
            }

            $serializer = $this->registry->getSerializer($class);
            $this->sideloadCollections[$class] = new ProcessingCollection([], $serializer);
            $this->initializeNestedCollections($serializer);
        }
    }

    private function collectDirtyObjects(Serializer $serializer, $item)
    {
        foreach ($serializer->getIncludes() as $class) {
            if ($class === $this->mainResourceClass) {
                continue;
            }

            if ($object = $serializer->collectIncludes($item, $class)) {
                $this->sideloadCollections[$class]->addUnprocessed($object);
            }
        }
    }

    private function processCollections()
    {
        foreach ($this->sideloadCollections as $collection) {
            $serializer = $collection->getSerializer();
            foreach ($collection->getUnprocessed() as $object) {
                $collection->process($object);
                $this->collectDirtyObjects($serializer, $object);
            }
        }

        $remaining = 0;
        foreach ($this->sideloadCollections as $collection) {
            $remaining += $collection->countUnprocessed();
        }

        if ($remaining > 0) {
            $this->processCollections();
        }
    }
}
