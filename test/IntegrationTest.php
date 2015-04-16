<?php

use Peterjmit\Serializer\Manager;
use Peterjmit\Serializer\Test\Fixture\SimpleBookSerializer;
use Peterjmit\Serializer\Test\Fixture\BookWithAuthorSerializer;
use Peterjmit\Serializer\Test\Fixture\UserSerializer;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializingResource()
    {
        $data = [
            'id' => 1,
            'title' => 'Life, the Universe and Everything'
        ];

        $manager = Manager::createJsonApi([new SimpleBookSerializer()]);
        $resource = $manager->createResource($data, 'book');

        $this->assertEquals([
            'data' => [
                'id' => 1,
                'type' => 'book',
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

        $manager = Manager::createJsonApi([new SimpleBookSerializer()]);
        $resource = $manager->createResourceCollection($data, 'book');

        $this->assertEquals([
            'data' => [
                [
                    'id' => 1,
                    'type' => 'book',
                    'title' => 'Life, the Universe and Everything',
                ],
                [
                    'id' => 2,
                    'type' => 'book',
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

        $manager = Manager::createJsonApi([new BookWithAuthorSerializer(), new UserSerializer()]);
        $resource = $manager->createResource($data, 'book');

        $this->assertEquals([
            'data' => [
                'id' => 1,
                'type' => 'book',
                'title' => 'Life, the Universe and Everything',
                'links' => [
                    'author' => [
                        'linkage' => ['type' => 'user', 'id' => 1]
                    ]
                ]
            ],
            'included' => [
                [
                    'id' => 1,
                    'type' => 'user',
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

        $manager = Manager::createJsonApi([new BookWithAuthorSerializer(), new UserSerializer()]);
        $resource = $manager->createResourceCollection($data, 'book');

        $this->assertEquals([
            'data' => [
                [
                    'id' => 1,
                    'type' => 'book',
                    'title' => 'Life, the Universe and Everything',
                    'links' => [
                        'author' => [
                            'linkage' => ['type' => 'user', 'id' => 1]
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'type' => 'book',
                    'title' => 'So Long, and Thanks for All the Fish',
                    'links' => [
                        'author' => [
                            'linkage' => ['type' => 'user', 'id' => 1]
                        ]
                    ]
                ]
            ],
            'included' => [
                [
                    'id' => 1,
                    'type' => 'user',
                    'name' => 'Douglas Adams',
                ]
            ]
        ], $manager->serialize($resource));
    }
}
