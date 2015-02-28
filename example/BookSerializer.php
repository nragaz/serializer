<?php

use Peterjmit\Serializer\Serializer;

class BookSerializer implements Serializer
{
    public function getIdentifier($book)
    {
        return $book['id'];
    }

    public function serialize($book)
    {
        return [
            'id' => $book['id'],
            'name' => $book['name'],
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

    public function collectIncludes($book, $name)
    {
        switch ($name) {
            case 'author':
                return $book['author'];
        }
    }
}
