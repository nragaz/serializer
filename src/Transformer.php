<?php

namespace Peterjmit\Serializer;

interface Transformer
{
    public function getIdentifier($object);
    public function transform($object);
    public function getKey();
    public function getPluralKey();
    public function getClass();
    public function getSideloadedClasses();
    public function collectSideloadedObject($object, $class);
    public function supportsClass($object);
}
