<?php

namespace Api\Repository\Entity\Transfers;

use Api\Repository\Entity\Standard\Repository;
use Api\Repository\Mapper\User\TransferMap;
use PDO;

class Transfers extends Repository
{
    protected static function getTableName(): string
    {
        return DB_BASE . '.transfers';
    }

    public function create(array $data): int
    {
        $conn = $this->getConnection();

        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::getTableName(),
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();

        return (int) $conn->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $conn = $this->getConnection();

        $setClause = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $sql = sprintf('UPDATE %s SET %s WHERE id_transfer = :id', static::getTableName(), $setClause);

        $stmt = $conn->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
