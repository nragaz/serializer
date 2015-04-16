<?php

namespace Peterjmit\Serializer\Test\Fixture;

use Peterjmit\Serializer\Serializer;

class SimpleBookSerializer implements Serializer
{
    public function getIdentifier($item)
    {
        return $item['id'];
    }

    public function serialize($item)
    {
        return [
            'id' => $item['id'],
            'type' => $this->getName(),
            'title' => $item['title'],
        ];
    }

    public function getName()
    {
        return 'book';
    }

    public function getIncludes()
    {
        return [];
    }

    public function collectIncludes($item, $name)
    {
    }
}
