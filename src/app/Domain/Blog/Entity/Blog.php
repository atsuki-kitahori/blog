<?php

namespace App\Domain\Blog\Entity;

use App\Domain\Blog\ValueObject\BlogContents;
use App\Domain\Blog\ValueObject\BlogTitle;

class Blog
{
    private ?int $id;
    private int $userId;
    private BlogTitle $title;
    private BlogContents $contents;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(
        int $userId,
        BlogTitle $title,
        BlogContents $contents,
        ?int $id = null,
        string $createdAt = '',
        string $updatedAt = ''
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->contents = $contents;
        $this->createdAt = $createdAt ?: date('Y-m-d H:i:s');
        $this->updatedAt = $updatedAt ?: date('Y-m-d H:i:s');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTitle(): BlogTitle
    {
        return $this->title;
    }

    public function getContents(): BlogContents
    {
        return $this->contents;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}
