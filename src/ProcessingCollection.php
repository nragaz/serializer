<?php

namespace Peterjmit\Serializer;

class ProcessingCollection implements Collection
{
    private $serializer;
    private $unprocessed;
    private $processed;

    public function __construct(array $unprocessed = [], Serializer $serializer)
    {
        $this->serializer = $serializer;

        $this->processed = [];
        $this->unprocessed = [];

        foreach ($unprocessed as $item) {
            $this->addUnprocessed($item);
        }
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
}
