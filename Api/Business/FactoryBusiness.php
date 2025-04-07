<?php

namespace Api\Business;

use Api\Business\Data\Data;

class FactoryBusiness
{
    private static $instance;
    private $repositoryName;
    private $className;

    public static function create(string $name, array $arguments = [])
    {
        if (self::$instance == null) {
            self::$instance = new FactoryBusiness();
        }
        return self::$instance->createInternal($name, $arguments);
    }

    private function createInternal(string $name, array $arguments)
    {
        $this->setClassName($name);
        
        if (strpos($this->className, 'Api\\Repository\\') !== false) {
            return $this->createRepository($arguments);
        }
        
        if ($this->hasBusinessCreated()) {
            return $this->getCreatedBusiness($arguments);
        }

        return $this->getAnonymousBusiness();
    }

    private function setClassName(string $name): void
    {
        $this->repositoryName = $name;
    
        // Verifique se o nome contém "Entity"
        if (strpos($name, 'Entity') !== false) {
            $this->className = 'Api\\Repository\\' . $name;
        } else {
            $this->className = 'Api\\Business\\' . $name;
        }
    }
    
    private function hasBusinessCreated(): bool
    {
        return class_exists($this->className);
    }

    private function getCreatedBusiness(array $arguments)
    {
        $className = $this->className;
        return new $className(...$arguments);
    }

    private function createRepository(array $arguments)
    {
        $repositoryClass = $this->className;
        return new $repositoryClass(...$arguments);
    }

    private function getAnonymousBusiness()
    {
        return new class($this->repositoryName) {
            use Data;
    
            public $anonymousName;
    
            public function __construct($name)
            {
                $this->anonymousName = $name;
            }

            public function init()
            {
            }
        };
    }
}

