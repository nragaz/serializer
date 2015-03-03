<?php

namespace Peterjmit\Serializer\Test\Fixture;

use Peterjmit\Serializer\Serializer;

class BookWithAuthorSerializer implements Serializer
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
            'author' => $item['author']['id'],
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
        return ['author'];
    }

    public function collectIncludes($item, $name)
    {
        switch ($name) {
            case 'author':
                return $item['author'];
        }
    }
}
