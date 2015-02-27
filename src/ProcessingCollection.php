<?php

namespace Peterjmit\Serializer;

class ProcessingCollection implements Collection
{
    private $unprocessed;
    private $processed;

    public function __construct(array $unprocessed = [], Transformer $transformer)
    {
        $this->transformer = $transformer;

        $this->processed = [];
        $this->unprocessed = [];

        foreach ($unprocessed as $object) {
            $this->addUnprocessed($object);
        }
    }

    public function getTransformer()
    {
        return $this->transformer;
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
        return new ResourceCollection($this->processed, $this->transformer);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->unprocessed);
    }

    private function getIdentifier($object)
    {
        return $this->transformer->getIdentifier($object);
    }
}
