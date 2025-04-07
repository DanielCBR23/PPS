<?php

namespace Api\Lib\Validator;

class ErrorDetails
{

    private static $instance,
        $detailsError = [],
        $detailsWarning = [];

    public static function getInstance(): ErrorDetails
    {
        if (self::$instance == null) {
            self::$instance = new ErrorDetails();
        }
        return self::$instance;
    }

    public function appendDetailWarning(string $key, $value): void
    {
        self::$detailsWarning[$key] = $value;
    }

    public function appendDetail(string $key, $value): void
    {
        self::$detailsError[$key] = $value;
    }

    public function getDetails(): array
    {
        $details = array_merge(self::$detailsWarning, self::$detailsError);
        return array_filter($details);
    }

    public function hasDetailsWarning(): bool
    {
        return !empty(self::$detailsWarning);
    }

    public function hasDetails(): bool
    {
        return !empty(self::$detailsError);
    }
}