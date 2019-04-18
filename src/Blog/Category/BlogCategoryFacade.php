<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\BlogRepository;
use Rixafy\Blog\Category\Constraint\BlogCategoryUniqueConstraint;
use Rixafy\Blog\Exception\BlogNotFoundException;

class BlogCategoryFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogCategoryRepository */
    private $blogCategoryRepository;

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
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function edit(BlogCategoryUniqueConstraint $id, BlogCategoryData $blogCategoryData): BlogCategory
    {
        $category = $this->blogCategoryRepository->get($id);
        $category->edit($blogCategoryData);

        $this->entityManager->flush();

        return $category;
    }

    /**
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function get(BlogCategoryUniqueConstraint $id): BlogCategory
    {
        return $this->blogCategoryRepository->get($id);
    }

    /**
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function remove(BlogCategoryUniqueConstraint $id, bool $permanent = false): void
    {
        $entity = $this->get($id);

        if ($permanent) {
            $this->entityManager->remove($entity);
        } else {
            $entity->remove();
        }

        $this->entityManager->flush();
    }
}