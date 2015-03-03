<?php

namespace Peterjmit\Serializer\Resource;

interface Resource
{
    public function getName();

    public function unwrap();

    public function getKey();

    public function serialize();

    public function getSerializer();
}
