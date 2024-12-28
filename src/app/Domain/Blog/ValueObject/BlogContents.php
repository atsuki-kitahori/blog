<?php

namespace App\Domain\Blog\ValueObject;

class BlogContents
{
    private string $value;

    public function __construct(string $contents)
    {
        if (empty($contents)) {
            throw new \InvalidArgumentException('内容は必須です');
        }
        $this->value = $contents;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
