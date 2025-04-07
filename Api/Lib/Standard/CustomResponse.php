<?php

namespace Api\Lib\Standard;

use Api\Repository\Mapper\Standard\Mapper;

class CustomResponse
{

    private static $instance,
        $info = [];

    public static function getInstance(): CustomResponse
    {
        if (!self::$instance instanceof CustomResponse) {
            self::$instance = new CustomResponse();
        }
        return self::$instance;
    }

    public function append(string $key, mixed $value): void
    {
        self::$info[$key] = $value;
    }

    public function appendInArray(string $array, mixed $value): void
    {
        self::$info[$array][] = $value;
    }

    public function appendMultiDimensionalArray(string $array, string $key, mixed $value): void
    {
        self::$info[$array][$key][] = $value;
    }

    public function appendMapper(string $key, Mapper $mapper): void
    {
        self::$info[$key] = $mapper->renderObject();
    }

    public function appendMapperArray(string $key, array $mappers): void
    {
        foreach ($mappers as $mapper) {
            $this->appendMoreMappersInKey($key, $mapper);
        }
    }

    public function appendMoreMappersInKey(string $key, Mapper $mapper): void
    {
        self::$info[$key][] =  $mapper->renderObject();
    }

    public function hasInfo(): bool
    {
        if (!$this->canEnvironmentResponseInfo()) {
            return false;
        }
        return !empty(self::$info);
    }

    public function getInfo(): array
    {
        if (!$this->canEnvironmentResponseInfo()) {
            return [];
        }
        return array_filter(self::$info);
    }

    public static function setInfo(array $info): void
    {
        self::$info = $info;
    }

    private function canEnvironmentResponseInfo(): bool
    {
        return IS_OFFLINE;
    }
}