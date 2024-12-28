<?php

namespace App\Infrastructure\Database;

use PDO;

class PDOConnection
{
    public static function getInstance(): PDO
    {
        return new PDO(
            'mysql:host=mysql;dbname=blog;charset=utf8mb4',
            'root',
            'password',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
} 