<?php

namespace Peterjmit\Serializer;

use Peterjmit\Serializer\Adapter\Adapter;
use Peterjmit\Serializer\Adapter\EmberDataAdapter;
use Peterjmit\Serializer\Adapter\JsonApiAdapter;
use Peterjmit\Serializer\IncludeMapping\IncludesCollector;
use Peterjmit\Serializer\Registry\SerializerRegistry;
use Peterjmit\Serializer\Registry\SimpleSerializerRegistry;
use Peterjmit\Serializer\Resource\Resource;
use Peterjmit\Serializer\Resource\SingleResource;
use Peterjmit\Serializer\Resource\ResourceCollection;

class Manager
{
    private $registry;
    private $collector;
    private $adapter;

    public function __construct(
        SerializerRegistry $registry,
        IncludesCollector $collector,
        Adapter $adapter
    ) {
        $this->registry = $registry;
        $this->collector = $collector;
        $this->adapter = $adapter;
    }

    public static function createEmberData(array $serializers)
    {
        $registry = new SimpleSerializerRegistry($serializers);

        return new static(
            $registry,
            new IncludesCollector($registry),
            new EmberDataAdapter()
        );
    }

    public static function createJsonApi(array $serializers)
    {
        $registry = new SimpleSerializerRegistry($serializers);

        return new static(
            $registry,
            new IncludesCollector($registry),
            new JsonApiAdapter()
        );
    }

    public function createResource($item, $name)
    {
        return new SingleResource($item, $this->registry->getSerializer($name));
    }

    public function createResourceCollection(array $items, $name)
    {
        return new ResourceCollection($items, $this->registry->getSerializer($name));
    }

    public function serialize(Resource $resource)
    {
        $sideload = $this->collector->collect($resource);

        return $this->adapter->serialize($resource, $sideload);
    }

    public function toJson(Resource $resource, $options = 0)
    {
        return json_encode($this->serialize($resource), $options);
    }
}
