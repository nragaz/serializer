<?php

use Peterjmit\Serializer\Serializer;

class PublisherSerializer implements Serializer
{
    public function getIdentifier($publisher)
    {
        return $publisher['id'];
    }

    public function serialize($publisher)
    {
        return [
            'id' => $publisher['id'],
            'hq' => $publisher['hq'],
        ];
    }

    public function getKey()
    {
        return 'publisher';
    }

    public function getPluralKey()
    {
        return 'publishers';
    }

    public function getName()
    {
        return 'publisher';
    }

    public function getIncludes()
    {
        return [];
    }

    public function collectIncludes($publisher, $name)
    {
    }
}
