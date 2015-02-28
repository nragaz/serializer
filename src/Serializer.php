<?php

namespace Peterjmit\Serializer;

interface Serializer
{
    public function getIdentifier($item);
    public function serialize($item);
    public function getKey();
    public function getPluralKey();
    public function getClass();
    public function getIncludes();
    public function collectIncludes($item, $class);
    public function supportsClass($item);
}
