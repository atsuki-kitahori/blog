<?php

namespace App\Domain\User\ValueObject;

class Password
{
    private string $hashedValue;

    public function __construct(string $password)
    {
        if (strlen($password) < 8) {
            throw new \InvalidArgumentException('パスワードは8文字以上である必要があります');
        }
        $this->hashedValue = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getHashedValue(): string
    {
        return $this->hashedValue;
    }

    public function verify(string $password): bool
    {
        return password_verify($password, $this->hashedValue);
    }
} 