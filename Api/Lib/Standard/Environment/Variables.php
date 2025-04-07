<?php

namespace Api\Lib\Standard\Environment;

use Api\Exceptions\FactoryException;

class Variables
{

    private static $instance = null;

    public static function getInstance(): self
    {
        if (!self::$instance instanceof Variables) {
            self::$instance = new Variables();
        }
        return self::$instance;
    }

    public function get(string $key): string
    {
        $value = getenv($key) ?: ($_ENV[$key] ?? null);
    
        if (empty($value)) {
            $exception = 'Standard\Environment\VariableNotFoundException';
            throw FactoryException::create($exception, [$key]);
        }
    
        return $value;
    }
    public function getAll(): array
    {
        return $_ENV;
    }    
}