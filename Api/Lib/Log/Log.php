<?php

namespace Api\Lib\Log;

use Api\Repository\Entity\Standard\Repository;
use PDO;

class Log extends Repository
{
    const LEVEL_INFO = 'INFO';
    const LEVEL_ERROR = 'ERROR';

    protected static function getTableName(): string
    {
        return DB_BASE . '.logs';
    }

    /**
     * Salva um log de informação
     */
    public function info(string $message, string $type, array $context = []): bool
    {
        return $this->saveLog(self::LEVEL_INFO, $type, $message, $context);
    }

    /**
     * Salva um log de erro
     */
    public function error(string $message, string $type, array $context = []): bool
    {
        return $this->saveLog(self::LEVEL_ERROR, $type, $message, $context);
    }

    /**
     * Método interno para salvar logs no banco
     */
    protected function saveLog(string $level, string $type, string $message, array $context = []): bool
    {
        try {
            $connection = $this->getConnection();
            $stmt = $connection->prepare(
                "INSERT INTO " . self::getTableName() . " 
                (level_log, type_log, message_log, context_log, created_log) 
                VALUES (:level, :type_log, :message, :context, :created_at)"
            );

            $contextJson = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : null;

            return $stmt->execute([
                ':level' => $level,
                ':type_log' => $type,
                ':message' => $message,
                ':context' => $contextJson,
                ':created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            error_log("Falha ao salvar log: " . $e->getMessage());
            return false;
        }
    }
}