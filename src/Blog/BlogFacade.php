<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class BlogFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogFactory */
    private $blogFactory;

    /**
     * BlogFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param BlogRepository $blogRepository
     * @param BlogFactory $blogFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        BlogRepository $blogRepository,
        BlogFactory $blogFactory
    ) {
        $this->entityManager = $entityManager;
        $this->blogRepository = $blogRepository;
        $this->blogFactory = $blogFactory;
    }

    /**
     * @param BlogData $blogData
     * @return Blog
     */
    public function create(BlogData $blogData): Blog
    {
        $blog = $this->blogFactory->create($blogData);

        $this->entityManager->persist($blog);
        $this->entityManager->flush();

        return $blog;
    }

    /**
     * @param UuidInterface $id
     * @param BlogData $blogData
     * @return Blog
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
     * @param UuidInterface $id
     * @return Blog
     * @throws Exception\BlogNotFoundException
     */
    public function get(UuidInterface $id): Blog
    {
        return $this->blogRepository->get($id);
    }
}