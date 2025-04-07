<?php

namespace Api\Repository\Entity\Standard;

use Api\Lib\Standard\Environment\Variables;
use PDO;
use PDOException;
use ReflectionClass;

abstract class Repository
{
    private $conn;

    abstract protected static function getTableName(): string;

    protected function getConnection()
    {
        if ($this->conn === null) {
            try {
                $variables = Variables::getInstance();
                $host = $variables->get('DB_HOST');
                $username = $variables->get('DB_USERNAME');
                $password = $variables->get('DB_PASSWORD');
                $port = $variables->get('DB_PORT');

                $dsn = sprintf('mysql:host=%s;port=%s;', $host, $port);
                $this->conn = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (PDOException $e) {
                echo "Erro na conexão: " . $e->getMessage();
            }
        }
        return $this->conn;
    }

    protected function transformList(mixed $data, string $mapperClass)
    {
        if (!$data) {
            return new $mapperClass;
        }

        if (isset($data[0]) && is_array($data[0])) {
            $mappedList = [];
            foreach ($data as $item) {
                $mappedList[] = $this->populateMapper($item, $mapperClass);
            }
            return $mappedList;
        }

        return $this->populateMapper($data, $mapperClass);
    }

    protected function populateMapper(array $data, string $mapperClass)
    {
        $reflectionClass = new \ReflectionClass($mapperClass);
        $mapperInstance = $reflectionClass->newInstance();

        foreach ($data as $key => $value) {
            $property = $this->convertToCamelCase($key); // Converte o nome da coluna para camelCase, se necessário

            if ($reflectionClass->hasProperty($property)) {
                $reflectionProperty = $reflectionClass->getProperty($property);
                $reflectionProperty->setAccessible(true); // Garante que a propriedade é acessível
                $reflectionProperty->setValue($mapperInstance, $value); // Define o valor no objeto
            } else {
                if (method_exists($mapperInstance, '__set')) {
                    $mapperInstance->__set($property, $value);
                } else {
                    // Adiciona um log para ver quais propriedades não foram mapeadas
                }
            }
        }
        return $mapperInstance;
    }

    private function convertToCamelCase(string $key): string
    {
        return lcfirst(str_replace('_', '', ucwords($key, '_')));
    }

    public function beginTransaction(): void
    {
        $this->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getConnection()->commit();
    }

    public function rollBack(): void
    {
        $this->getConnection()->rollBack();
    }
}
