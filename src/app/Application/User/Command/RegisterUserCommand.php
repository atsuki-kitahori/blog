<?php

namespace App\Application\User\Command;

class RegisterUserCommand
{
    private string $userName;
    private string $email;
    private string $password;

    public function __construct(string $userName, string $email, string $password)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
    }

    public function getUserName(): string
    {
        return $this->userName;
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