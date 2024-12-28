<?php

namespace App\Domain\User\ValueObject;

class UserName
{
    private string $value;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('ユーザー名は必須です');
        }
        $this->value = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }
} 