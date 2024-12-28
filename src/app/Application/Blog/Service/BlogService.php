<?php

namespace App\Application\Blog\Service;

use App\Application\Blog\Command\CreateBlogCommand;
use App\Domain\Blog\Entity\Blog;
use App\Domain\Blog\Repository\BlogRepositoryInterface;
use App\Domain\Blog\ValueObject\BlogContents;
use App\Domain\Blog\ValueObject\BlogTitle;

class BlogService
{
    private BlogRepositoryInterface $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function create(CreateBlogCommand $command): bool
    {
        $blog = new Blog(
            $command->getUserId(),
            new BlogTitle($command->getTitle()),
            new BlogContents($command->getContents())
        );

        return $this->blogRepository->save($blog);
    }
} 