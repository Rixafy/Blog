<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Site\RouteSite;

class BlogFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogFactory */
    private $blogFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        BlogRepository $blogRepository,
        BlogFactory $blogFactory
    ) {
        $this->entityManager = $entityManager;
        $this->blogRepository = $blogRepository;
        $this->blogFactory = $blogFactory;
    }

    public function create(BlogData $blogData, RouteSite $routeSite): Blog
    {
        $blog = $this->blogFactory->create($blogData, $routeSite);

        $this->entityManager->persist($blog);
        $this->entityManager->flush();

        return $blog;
    }

    /**
     * @throws Exception\BlogNotFoundException
     */
    public function edit(UuidInterface $id, BlogData $blogData): Blog
    {
        $blog = $this->blogRepository->get($id);
        $blog->edit($blogData);

        $this->entityManager->flush();

        return $blog;
    }

    /**
     * @throws Exception\BlogNotFoundException
     */
    public function get(UuidInterface $id): Blog
    {
        return $this->blogRepository->get($id);
    }
}
