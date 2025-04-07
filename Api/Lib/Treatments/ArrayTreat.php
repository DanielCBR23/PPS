<?php

namespace Api\Lib\Treatments;

class ArrayTreat
{
    public static function get(array $data, string $index, $notFoundReturn = ''): mixed
    {
        if (self::hasIndex($data, $index)) {
            return $data[$index];
        }
        return $notFoundReturn;
    }

    public static function append(array &$data, string $index, $value): void
    {
        if (!self::hasIndex($data, $index)) {
            $data[$index] = $value;
        }
    }

    public static function appendInArray(array &$data, string $index, $value): void
    {
        if (!self::hasIndex($data, $index)) {
            $data[$index] = array();
        }

        $data[$index][] = $value;
    }

    public static function remove(array &$data, string $index): void
    {
        if (self::hasIndex($data, $index)) {
            unset($data[$index]);
        }
    }

    public static function hasIndex(array $data, string $index): bool
    {
        return isset($data[$index]);
    }

    public static function appendArrayKey(array $data, string $column): array
    {
        $treatedData = array_reduce(
            $data,
            fn (array $accumulator, array $row) => [
                ...$accumulator,
                $row[$column] => $row
            ],
            []
        );

        ksort($treatedData);
        return $treatedData;
    }

    public static function chunkByColumn(array $data, string $column): array
    {
        $accumulator = array();

        $callbackFunction = function (array $chunkedData, array $row) use ($column) {
            ArrayTreat::appendInArray(
                $chunkedData,
                $row[$column],
                $row
            );

            return $chunkedData;
        };

        return array_reduce(
            $data,
            $callbackFunction,
            $accumulator
        );
    }

    public static function join(array $data): array
    {
        $data = array_values($data);

        return array_reduce(
            $data,
            fn (array $accumulator, array $innerArray)
            => array_merge($accumulator, $innerArray),
            array()
        );
    }
}