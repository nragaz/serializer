<?php

namespace Peterjmit\Serializer\IncludeMapping;

use Peterjmit\Serializer\Resource;
use Peterjmit\Serializer\Serializer;

class ProcessingCollections
{
    private $resourceName;
    private $collections;

    public function __construct($parentResourceName, array $serializers)
    {
        $this->resourceName = $parentResourceName;
        $this->collections = $this->initializeCollections($serializers);
    }

    public function process(Serializer $serializer, $item)
    {
        foreach ($serializer->getIncludes() as $name) {
            if ($name === $this->resourceName) {
                continue;
            }

            if ($includedItem = $serializer->collectIncludes($item, $name)) {
                $this->collections[$name]->addUnprocessed($includedItem);
            }
        }
    }

    public function processNested()
    {
        foreach ($this->collections as $collection) {
            $serializer = $collection->getSerializer();
            foreach ($collection->getUnprocessed() as $item) {
                $collection->process($item);
                $this->process($serializer, $item);
            }
        }

        if ($this->countUnprocessed() > 0) {
            $this->processNested();
        }
    }

    public function toResourceCollections()
    {
        $resourceCollections = [];
        foreach($this->collections as $collection) {
            if ($collection->countProcessed() > 0) {
                $resourceCollections[] = $collection->toResourceCollection();
            }
        }

        return $resourceCollections;
    }

    private function countUnprocessed()
    {
        return array_reduce($this->collections, function ($carry, $collection) {
            return $carry + $collection->countUnprocessed();
        }, 0);
    }

    private function initializeCollections(array $serializers)
    {
        $collections = [];
        foreach ($serializers as $name => $serializer) {
            if ($name !== $this->resourceName) {
                $collections[$name] = new ProcessingCollection($serializer);
            }
        }

        return $collections;
    }
}
