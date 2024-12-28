<?php

namespace App\Domain\Blog\Repository;

use App\Domain\Blog\Entity\Blog;

interface BlogRepositoryInterface
{
    public function save(Blog $blog): bool;
}
