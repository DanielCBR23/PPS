<?php

namespace tests\Database;

use PDO;

class Database
{
    public static function getConnection(): PDO
    {
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=dbpps';
        $username = 'root';
        $password = 'root';

        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
