<?php

namespace App\Infrastructure\Blog\Repository;

use App\Domain\Blog\Entity\Blog;
use App\Domain\Blog\Repository\BlogRepositoryInterface;
use PDO;

class BlogRepository implements BlogRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Blog $blog): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO blogs (user_id, title, contents, created_at, updated_at) VALUES (?, ?, ?, ?, ?)'
        );

        return $stmt->execute([
            $blog->getUserId(),
            $blog->getTitle()->getValue(),
            $blog->getContents()->getValue(),
            $blog->getCreatedAt(),
            $blog->getUpdatedAt(),
        ]);
    }
}
