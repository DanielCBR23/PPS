<?php

namespace Api\Repository\Entity\Wallets;

use Api\Repository\Entity\Standard\Repository;
use Api\Repository\Mapper\Wallet\WalletMap;
use PDO;

class Wallets extends Repository
{

    protected static function getTableName(): string
    {
        return DB_BASE . '.wallets';
    }

    public function getWalletByIdUser(string $userId): WalletMap
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM " . self::getTableName() . " WHERE userId_wallet = :user");
        $stmt->bindParam(':user', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->transformList($result, WalletMap::class);
    }

    public function updateBalance(WalletMap $wallet): bool
    {
        $userId = $wallet->userIdWallet;
        $balance = $wallet->balanceWallet;

        $conn = $this->getConnection();
        $stmt = $conn->prepare("UPDATE " . self::getTableName() . " SET balance_wallet = :value WHERE userId_wallet = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':value', $balance);
        return $stmt->execute();
    }

    public function insert(string $userId): void
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("INSERT INTO " . self::getTableName() . " (userId_wallet, balance_wallet) VALUES (:userId, :balance)");
        $stmt->bindParam(':userId', $userId);
        $balance = 0.00;
        $stmt->bindParam(':balance', $balance);
        $stmt->execute();
    }
}