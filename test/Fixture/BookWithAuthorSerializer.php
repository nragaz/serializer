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
            'type' => $this->getName(),
            'title' => $item['title'],
            'links' => [
                'author' => [
                    'linkage' => ['type' => 'user', 'id' => $item['author']['id']]
                ]
            ]
        ];
    }

    public function getName()
    {
        return 'book';
    }

    public function getIncludes()
    {
        return ['user'];
    }

    public function collectIncludes($item, $name)
    {
        switch ($name) {
            case 'user':
                return [$item['author']];
        }
    }
}
