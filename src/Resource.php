<?php

namespace Peterjmit\Serializer;

interface Resource
{
    public function getClass();

    public function unwrap();

    public function getKey();

    public function serialize();

    public function getSerializer();
}
