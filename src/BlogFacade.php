<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\EntityManagerInterface;

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
     * @param string $id
     * @param BlogData $blogData
     * @return Blog
     * @throws Exception\BlogNotFoundException
     */
    public function edit(string $id, BlogData $blogData): Blog
    {
        $blog = $this->blogRepository->get($id);
        $blog->edit($blogData);

        $this->entityManager->flush();

        return $blog;
    }

    /**
     * @param string $id
     * @return Blog
     * @throws Exception\BlogNotFoundException
     */
    public function get(string $id): Blog
    {
        return $this->blogRepository->get($id);
    }
}