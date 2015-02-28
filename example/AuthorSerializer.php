<?php

use Peterjmit\Serializer\Serializer;

class AuthorSerializer implements Serializer
{
    public function getIdentifier($author)
    {
        return $author['id'];
    }

    public function serialize($author)
    {
        return [
            'id' => $author['id'],
            'full_name' => $author['fullName'],
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
        return ['publisher'];
    }

    public function collectIncludes($author, $name)
    {
        switch ($name) {
            case 'publisher':
                return $author['publisher'];
        }
    }
}
