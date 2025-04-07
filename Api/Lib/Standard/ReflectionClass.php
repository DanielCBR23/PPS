<?php

namespace Api\Lib\Standard;


class ReflectionClass
{

    public static function getShortName($obj): string
    {
        return $obj->getShortNameUsingClassName();
    }

    public static function getAllClassNameInnerPath(string $path): array
    {
        if (!is_dir($path)) {
            return [];
        }
        $classNames = array_slice(scandir($path), 2);
        return array_map(function (string $value) {
            return str_replace('.php', '', $value);
        }, array_filter($classNames, function (string $value) {
            return strpos($value, '.php') !== false;
        }));
    }

    public static function implementInterface($obj, string $interfaceName): bool
    {
        $interfaces = class_implements($obj);
        return (isset($interfaces[$interfaceName]));
    }
}