<?php

namespace App\Domain\Auth\Repository;

use App\Domain\Auth\ValueObject\Credentials;

interface AuthRepositoryInterface
{
    public function findByCredentials(Credentials $credentials): ?array;
}
