<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogRepository;
use Rixafy\Blog\Exception\BlogNotFoundException;

class BlogCategoryFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogCategoryRepository */
    private $blogCategoryRepository;

    /**
     * BlogCategoryFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param BlogRepository $blogRepository
     * @param BlogCategoryRepository $blogCategoryRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        BlogRepository $blogRepository,
        BlogCategoryRepository $blogCategoryRepository
    ) {
        $this->blogRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogCategoryRepository = $blogCategoryRepository;
    }

    /**
     * @param UuidInterface $blogId
     * @param BlogCategoryData $blogCategoryData
     * @return BlogCategory
     * @throws BlogNotFoundException
     */
    public function create(UuidInterface $blogId, BlogCategoryData $blogCategoryData): BlogCategory
    {
        $blog = $this->blogRepository->get($blogId);
        $category = $blog->addCategory($blogCategoryData);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param BlogCategoryData $blogCategoryData
     * @return BlogCategory
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function edit(UuidInterface $id, UuidInterface $blogId, BlogCategoryData $blogCategoryData): BlogCategory
    {
        $category = $this->blogCategoryRepository->get($id, $blogId);
        $category->edit($blogCategoryData);

        $this->entityManager->flush();

        return $category;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @return BlogCategory
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogCategory
    {
        return $this->blogCategoryRepository->get($id, $blogId);
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param bool $permanent
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function remove(UuidInterface $id, UuidInterface $blogId, bool $permanent = false): void
    {
        $entity = $this->get($id, $blogId);

        if ($permanent) {
            $this->entityManager->remove($entity);
        } else {
            $entity->remove();
        }

        $this->entityManager->flush();
    }
}