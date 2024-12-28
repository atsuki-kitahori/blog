<?php

namespace App\Application\Auth\Service;

use App\Application\Auth\Command\LoginCommand;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Auth\ValueObject\Credentials;

class AuthService
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginCommand $command): ?array
    {
        $credentials = new Credentials(
            $command->getEmail(),
            $command->getPassword()
        );
        return $this->authRepository->findByCredentials($credentials);
    }
}
