<?php

namespace Peterjmit\Serializer\IncludeMapping;

use Peterjmit\Serializer\Serializer;
use Peterjmit\Serializer\Resource\ResourceCollection;

class ProcessingCollection
{
    private $serializer;
    private $unprocessed;
    private $processed;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
        $this->processed = [];
        $this->unprocessed = [];
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

    public function addUnprocessed($item)
    {
        $id = $this->getIdentifier($item);

        if (!$this->isProcessed($id)) {
            $this->unprocessed[$id] = $item;
        }
    }

    public function addManyUnprocessed($items)
    {
        if (!$this->isMultiDimensionalArray($items)) {
            $items = [$items];
        }

        foreach ($items as $item) {
            $this->addUnprocessed($item);
        }
    }

    public function process($item)
    {
        $id = $this->getIdentifier($item);

        unset($this->unprocessed[$id]);

        $this->processed[$id] = $item;
    }

    public function getUnprocessed()
    {
        return $this->unprocessed;
    }

    public function countUnprocessed()
    {
        return count($this->unprocessed);
    }

    private function isProcessed($id)
    {
        return isset($this->processed[$id]);
    }

    public function toResourceCollection()
    {
        return new ResourceCollection($this->processed, $this->serializer);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->unprocessed);
    }

    public function countProcessed()
    {
        return count($this->processed);
    }

    private function getIdentifier($item)
    {
        return $this->serializer->getIdentifier($item);
    }

    private function isMultiDimensionalArray($item)
    {
        if (!is_array($item) || !($item instanceof \Traversable)) {
            return false;
        }

        foreach ($item as $value) {
            return is_array($value);
        }

        throw new \UnexpectedValueException('Unexpected empty array');
    }
}
