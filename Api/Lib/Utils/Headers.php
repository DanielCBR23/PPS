<?php

namespace Api\Lib\Utils;

use Api\Exceptions\FactoryException;
use Api\Lib\AutomatedTest\AutomatedTestConfig;

class Headers
{

    private static $instance = null;
    private $headers = [];

    public static function getInstance(): self
    {
        if (!self::$instance instanceof Headers) {
            self::$instance = new Headers();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->setAllHeaders();
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    private function setAllHeaders(): void
    {
        // if (AutomatedTestConfig::isAutomatedTest()) {
        //     $this->headers = [];
        //     return;
        // }

        $this->headers = apache_request_headers();
    }

    public function get(string $name, string $notFoundReturn = ''): string
    {
        $headers = $this->getHeaders();
        foreach ($headers as $key => $header) {
            if (strtolower($key) != strtolower($name)) {
                continue;
            }
            
            $headerData = explode(' ', $header);
            $content = end($headerData);
            return str_replace(' ', '', $content);
        }
        return $notFoundReturn;
    }

    public function has(string $name): bool
    {
        return $this->get($name) != '';
    }

    public function getCompanyGalaxPayId(): int
    {
        $key = 'Company';
        if (!$this->has($key)) {
            $exc = 'Standard\Current\Company\InvalidAuthenticationException';
            throw FactoryException::create($exc);
        }
        return (int) $this->get($key);
    }

    public function getUserGalaxPayId(): int
    {
        if (!$this->hasUserGalaxPayId()) {
            $exc = 'Standard\Current\Company\User\InvalidHeaderException';
            throw FactoryException::create($exc);
        }
        return (int) $this->get('User-Id');
    }

    public function hasUserGalaxPayId(): bool
    {
        return $this->has('User-Id');
    }

    public function isAdmin(): bool
    {
        $key = 'Custom-User-Admin';
        if ($this->has($key)) {
            return (bool) $this->get($key);
        }
        return false;
    }
}