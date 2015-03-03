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
            'title' => $item['title'],
        ];
    }

    public function getKey()
    {
        return 'book';
    }

    public function getPluralKey()
    {
        return 'books';
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
