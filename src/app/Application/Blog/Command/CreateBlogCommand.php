<?php

namespace App\Application\Blog\Command;

class CreateBlogCommand
{
    private int $userId;
    private string $title;
    private string $contents;

    public function __construct(int $userId, string $title, string $contents)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->contents = $contents;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContents(): string
    {
        return $this->contents;
    }
}
