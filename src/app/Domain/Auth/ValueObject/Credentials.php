<?php

namespace App\Domain\Auth\ValueObject;

class Credentials
{
    private string $email;
    private string $password;

    public function __construct(string $email, string $password)
    {
        if (empty($email) || empty($password)) {
            throw new \InvalidArgumentException(
                'メールアドレスとパスワードは必須です'
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('無効なメールアドレス形式です');
        }

        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
