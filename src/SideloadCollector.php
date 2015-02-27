<?php

namespace Peterjmit\Serializer;

class SideloadCollector
{
    private $registry;
    private $sideloadCollections;
    private $mainResourceClass;

    public function __construct(TransformerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function collect(Resource $resource)
    {
        $this->mainResourceClass = $resource->getClass();
        $this->sideloadCollections = [];

        $transformer = $resource->getTransformer();

        // Recursively find all sideloading required for this object and nested
        // objects. Initialize collections for those objects
        $this->initializeNestedCollections($transformer);

        // Load direct children of the resource into collections
        if ($resource instanceof Collection) {
            foreach ($resource as $item) {
                $this->collectDirtyObjects($transformer, $item);
            }
        } else {
            $this->collectDirtyObjects($transformer, $resource->getObject());
        }

        // Recursively load the tree of nested objects into the collections
        $this->processCollections();

        // Return ResourceCollection[] instead of
        // instances of ProcessingCollection
        return array_map(function (ProcessingCollection $collection) {
            return $collection->toResourceCollection();
        }, $this->sideloadCollections);
    }

    private function initializeNestedCollections(Transformer $transformer)
    {
        foreach ($transformer->getSideloadedClasses() as $class) {
            if ($class === $this->mainResourceClass || isset($this->sideloadCollections[$class])) {
                continue;
            }

            $transformer = $this->registry->getTransformer($class);
            $this->sideloadCollections[$class] = new ProcessingCollection([], $transformer);
            $this->initializeNestedCollections($transformer);
        }
    }

    private function collectDirtyObjects(Transformer $transformer, $item)
    {
        foreach ($transformer->getSideloadedClasses() as $class) {
            if ($class === $this->mainResourceClass) {
                continue;
            }
            $object = $transformer->collectSideloadedObject($item, $class);

            $object && $this->sideloadCollections[$class]->addUnprocessed($object);
        }
    }

    private function processCollections()
    {
        foreach ($this->sideloadCollections as $collection) {
            $transformer = $collection->getTransformer();
            foreach ($collection->getUnprocessed() as $object) {
                $collection->process($object);
                $this->collectDirtyObjects($transformer, $object);
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
