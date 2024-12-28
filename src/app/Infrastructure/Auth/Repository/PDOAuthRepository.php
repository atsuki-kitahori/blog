<?php

namespace App\Infrastructure\Auth\Repository;

use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Auth\ValueObject\Credentials;
use PDO;

class PDOAuthRepository implements AuthRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByCredentials(Credentials $credentials): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$credentials->getEmail()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($credentials->getPassword(), $user['password'])) {
            return $user;
        }

        return null;
    }
} 