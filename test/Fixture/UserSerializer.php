<?php

namespace Peterjmit\Serializer\Test\Fixture;

use Peterjmit\Serializer\Serializer;

class UserSerializer implements Serializer
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
            'name' => $item['name'],
        ];
    }

    public function getName()
    {
        return 'user';
    }

    public function getIncludes()
    {
        return [];
    }

    public function collectIncludes($item, $name)
    {
    }
}
