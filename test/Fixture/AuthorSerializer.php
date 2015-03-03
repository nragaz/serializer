<?php

namespace Peterjmit\Serializer\Test\Fixture;

use Peterjmit\Serializer\Serializer;

class AuthorSerializer implements Serializer
{
    public function getIdentifier($item)
    {
        return $item['id'];
    }

    public function serialize($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
        ];
    }

    public function getKey()
    {
        return 'author';
    }

    public function getPluralKey()
    {
        return 'authors';
    }

    public function getName()
    {
        return 'author';
    }

    public function getIncludes()
    {
        return [];
    }

    public function collectIncludes($item, $name)
    {
    }
}
