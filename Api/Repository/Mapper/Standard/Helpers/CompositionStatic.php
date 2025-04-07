<?php

namespace Api\Repository\Mapper\Standard\Helpers;

use Api\Exceptions\FactoryException;

trait CompositionStatic
{

    private static $compositionStatic;

    protected static function getCompositionStatic($class)
    {
        if (isset(self::$compositionStatic[$class])) {
            return self::$compositionStatic[$class];
        }
        $name = $class . ' em ' . get_class();
        $exception = 'Standard\Mapper\CompositionNotSetException';
        throw FactoryException::create($exception, [$name]);
    }

    public static function appendCompositionStatic($obj, $class)
    {
        $class = str_replace('\Api\Repository\Mapper\\', '', $class);
        $class = str_replace('Map', '', $class);
        if (isset(self::$compositionStatic[$class])) {
            $exception = 'Standard\Mapper\CompositionStatic\SettedInfoCompositionException';
            throw FactoryException::create($exception, [$class]);
        }
        self::$compositionStatic[$class] = $obj;
    }
}