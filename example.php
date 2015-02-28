<?php

require __DIR__ . '/vendor/autoload.php';

use Peterjmit\Serializer\SerializerRegistry;
use Peterjmit\Serializer\IncludesCollector;
use Peterjmit\Serializer\Adapter\JsonApiAdapter;
use Peterjmit\Serializer\Adapter\EmberDataAdapter;

$registry = new SerializerRegistry();
$collector = new IncludesCollector($registry);

$registry->register(new BookSerializer());
$registry->register(new AuthorSerializer());
$registry->register(new PublisherSerializer());

$books = [];
for ($i=0; $i < 5; $i++) {
    $publisher = [];
    $publisher['id'] = $i;
    $publisher['hq'] = 'London #' . $i;

    $author = [];
    $author['id'] = $i;
    $author['fullName'] = 'Terry Nutkins #' . $i;
    $author['publisher'] = $publisher;

    $book = [];
    $book['id'] = $i;
    $book['name'] = 'Book #' . $i;
    $book['author'] = $author;

    $books[] = $book;
}

// Resource
$resource = $registry->createResourceCollection($books, 'book');
// ResourceCollection[]
$includes = $collector->collect($resource);

$adapter = new JsonApiAdapter();
// $adapter = new EmberDataAdapter();

$json = json_encode($adapter->serialize($resource, $includes), JSON_PRETTY_PRINT);

echo $json;

// Outputs:
// {
//     "data": [
//         {
//             "id": 0,
//             "name": "Book #0"
//         },
//         {
//             "id": 1,
//             "name": "Book #1"
//         },
//         {
//             "id": 2,
//             "name": "Book #2"
//         },
//         {
//             "id": 3,
//             "name": "Book #3"
//         },
//         {
//             "id": 4,
//             "name": "Book #4"
//         }
//     ],
//     "linked": [
//         {
//             "id": 0,
//             "full_name": "Terry Nutkins #0"
//         },
//         {
//             "id": 1,
//             "full_name": "Terry Nutkins #1"
//         },
//         {
//             "id": 2,
//             "full_name": "Terry Nutkins #2"
//         },
//         {
//             "id": 3,
//             "full_name": "Terry Nutkins #3"
//         },
//         {
//             "id": 4,
//             "full_name": "Terry Nutkins #4"
//         },
//         {
//             "id": 0,
//             "hq": "London #0"
//         },
//         {
//             "id": 1,
//             "hq": "London #1"
//         },
//         {
//             "id": 2,
//             "hq": "London #2"
//         },
//         {
//             "id": 3,
//             "hq": "London #3"
//         },
//         {
//             "id": 4,
//             "hq": "London #4"
//         }
//     ]
// }
