<?php

namespace Peterjmit\Serializer;

interface Collection extends \IteratorAggregate
{
    public function getTransformer();
}
