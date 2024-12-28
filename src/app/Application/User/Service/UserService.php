<?php

namespace App\Application\User\Service;

use App\Application\User\Command\RegisterUserCommand;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\UserName;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterUserCommand $command): bool
    {
        $email = new Email($command->getEmail());

        if ($this->userRepository->findByEmail($email)) {
            throw new \RuntimeException(
                'このメールアドレスは既に登録されています'
            );
        }

        $user = new User(
            new UserName($command->getUserName()),
            $email,
            new Password($command->getPassword())
        );

        return $this->userRepository->save($user);
    }
}
