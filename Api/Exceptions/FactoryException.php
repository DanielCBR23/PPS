<?php

namespace Api\Exceptions;

use Exception;

class FactoryException
{

    public static function create(string $name, array $arguments = []): Exception
    {
        $className = 'Api\\Exceptions\\' . $name;
        return new $className(...$arguments);
    }

    public static function createByClass(string $className, array $arguments = []): Exception
    {
        return new $className(...$arguments);
    }
}