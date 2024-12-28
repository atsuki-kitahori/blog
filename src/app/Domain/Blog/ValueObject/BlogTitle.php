<?php

namespace App\Domain\Blog\ValueObject;

class BlogTitle
{
    private string $value;

    public function __construct(string $title)
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('タイトルは必須です');
        }
        $this->value = $title;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
