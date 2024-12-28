<?php

namespace App\Domain\User\ValueObject;

class Email
{
    private string $value;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('無効なメールアドレス形式です');
        }
        $this->value = $email;
    }

    public function getValue(): string
    {
        return $this->value;
    }
} 