<?php

namespace Api\Business\Data\HealthCheck\Checks;

use Api\Lib\Standard\Environment\Variables;
use Exception;
use PDO;
use PDOException;

trait Database
{

    private ?PDO $conn = null;
    
    protected function checkDatabase(): void
    {
        try {
            $conn = $this->getConnection();
            $isWorking = ($conn instanceof PDO);
        } catch (Exception $exc) {
            $isWorking = false;
        } finally {
            $this->appendCheck('databaseWorking', $isWorking);
        }
    }

    protected function getConnection(): ?PDO
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
                error_log("Database connection error: " . $e->getMessage());
                $this->conn = null;
            }
        }
        return $this->conn;
    }
}