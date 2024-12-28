<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\UserName;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)'
        );

        return $stmt->execute([
            $user->getUserName()->getValue(),
            $user->getEmail()->getValue(),
            $user->getPassword()->getHashedValue(),
            $user->getCreatedAt(),
            $user->getUpdatedAt(),
        ]);
    }

    public function findByEmail(Email $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email->getValue()]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        return new User(
            new UserName($userData['name']),
            new Email($userData['email']),
            new Password($userData['password']),
            $userData['id'],
            $userData['created_at'],
            $userData['updated_at']
        );
    }
}
