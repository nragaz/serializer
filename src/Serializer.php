<?php

namespace Peterjmit\Serializer;

interface Serializer
{
    public function getIdentifier($item);
    public function serialize($item);
    public function getKey();
    public function getPluralKey();
    public function getName();
    public function getIncludes();
    public function collectIncludes($item, $name);
}
