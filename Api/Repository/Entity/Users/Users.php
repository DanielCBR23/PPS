<?php

namespace Api\Repository\Entity\Users;

use Api\Repository\Entity\Standard\Repository;
use Api\Repository\Mapper\User\UserMap;
use PDO;

class Users extends Repository
{
    protected static function getTableName(): string
    {
        return DB_BASE . '.users';
    }

    public function getUserById(string $user): UserMap
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM " . self::getTableName() . " WHERE id_user = :user");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->transformList($result, UserMap::class);
    }

    public function getUserByEmail(string $email): bool
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM " . self::getTableName() . " WHERE email_user = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false;
    }

    public function getUserByDocument(string $document): bool
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM " . self::getTableName() . " WHERE document_user = :document");
        $stmt->bindParam(':document', $document);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false;
    }

    public function insert(array $data): ?int
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("
                INSERT INTO " . self::getTableName() . " 
                (name_user, document_user, email_user, password_user, type_user) 
                VALUES (:name, :document, :email, :password, :type)
            ");

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':document', $data['document']);
        $stmt->bindParam(':email', $data['email']);
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':type', $data['typeUser']);
        $stmt->execute();
        return (int) $conn->lastInsertId();
    }
}
