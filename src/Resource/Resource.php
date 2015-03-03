<?php

namespace Peterjmit\Serializer\Resource;

interface Resource
{
    public function getKey();

    public function serialize();

    public function getSerializer();

    public function unwrap();
}
