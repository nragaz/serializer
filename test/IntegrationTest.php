<?php

use Peterjmit\Serializer\Manager;
use Peterjmit\Serializer\Test\Fixture\SimpleBookSerializer;
use Peterjmit\Serializer\Test\Fixture\BookWithAuthorSerializer;
use Peterjmit\Serializer\Test\Fixture\AuthorSerializer;

class EmberDataTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializingResource()
    {
        $data = [
            'id' => 1,
            'title' => 'Life, the Universe and Everything'
        ];

        $manager = Manager::createEmberData([new SimpleBookSerializer()]);
        $resource = $manager->createResource($data, 'book');

        $this->assertEquals([
            'book' => [
                'id' => 1,
                'title' => 'Life, the Universe and Everything'
            ]
        ], $manager->serialize($resource));
    }

    public function testSerializingResourceCollection()
    {
        $data = [
            [
                'id' => 1,
                'title' => 'Life, the Universe and Everything',
            ],
            [
                'id' => 2,
                'title' => 'So Long, and Thanks for All the Fish',
            ]
        ];

        $manager = Manager::createEmberData([new SimpleBookSerializer()]);
        $resource = $manager->createResourceCollection($data, 'book');

        $this->assertEquals([
            'books' => [
                [
                    'id' => 1,
                    'title' => 'Life, the Universe and Everything',
                ],
                [
                    'id' => 2,
                    'title' => 'So Long, and Thanks for All the Fish',
                ]
            ]
        ], $manager->serialize($resource));
    }

    public function testSerializingResourceWithNestedResource()
    {
        $data = [
            'id' => 1,
            'title' => 'Life, the Universe and Everything',
            'author' => [
                'id' => 1,
                'name' => 'Douglas Adams',
            ]
        ];

        $manager = Manager::createEmberData([new BookWithAuthorSerializer(), new AuthorSerializer()]);
        $resource = $manager->createResource($data, 'book');

        $this->assertEquals([
            'book' => [
                'id' => 1,
                'title' => 'Life, the Universe and Everything',
                'author' => 1,
            ],
            'authors' => [
                [
                    'id' => 1,
                    'name' => 'Douglas Adams',
                ]
            ]
        ], $manager->serialize($resource));
    }

    public function testSerializingResourceWithDuplicatedNestedResources()
    {
        $author = [
            'id' => 1,
            'name' => 'Douglas Adams',
        ];

        $data = [
            [
                'id' => 1,
                'title' => 'Life, the Universe and Everything',
                'author' => $author,
            ],
            [
                'id' => 2,
                'title' => 'So Long, and Thanks for All the Fish',
                'author' => $author,
            ]
        ];

        $manager = Manager::createEmberData([new BookWithAuthorSerializer(), new AuthorSerializer()]);
        $resource = $manager->createResourceCollection($data, 'book');

        $this->assertEquals([
            'books' => [
                [
                    'id' => 1,
                    'title' => 'Life, the Universe and Everything',
                    'author' => 1,
                ],
                [
                    'id' => 2,
                    'title' => 'So Long, and Thanks for All the Fish',
                    'author' => 1,
                ]
            ],
            'authors' => [
                [
                    'id' => 1,
                    'name' => 'Douglas Adams',
                ]
            ]
        ], $manager->serialize($resource));
    }
}
