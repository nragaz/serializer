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

        foreach ($unprocessed as $object) {
            $this->addUnprocessed($object);
        }
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

    public function addUnprocessed($object)
    {
        $id = $this->getIdentifier($object);

        if (!$this->isProcessed($id)) {
            $this->unprocessed[$id] = $object;
        }
    }

    public function process($object)
    {
        $id = $this->getIdentifier($object);

        unset($this->unprocessed[$id]);

        $this->processed[$id] = $object;
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

    private function getIdentifier($object)
    {
        return $this->serializer->getIdentifier($object);
    }
}
