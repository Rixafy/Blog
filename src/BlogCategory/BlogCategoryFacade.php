<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogCategory;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogRepository;

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
     * @param string $blogId
     * @param BlogCategoryData $blogCategoryData
     * @return BlogCategory
     * @throws \Rixafy\Blog\Exception\BlogNotFoundException
     */
    public function create(string $blogId, BlogCategoryData $blogCategoryData): BlogCategory
    {
        $blog = $this->blogRepository->get($blogId);
        $category = $blog->addCategory($blogCategoryData);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    /**
     * @param string $id
     * @param BlogCategoryData $blogCategoryData
     * @param Blog|null $blog
     * @return BlogCategory
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function edit(string $id, BlogCategoryData $blogCategoryData, Blog $blog = null): BlogCategory
    {
        $category = $this->blogCategoryRepository->get($id, $blog);
        $category->edit($blogCategoryData);

        $this->entityManager->flush();

        return $category;
    }

    /**
     * @param string $id
     * @param Blog|null $blog
     * @return BlogCategory
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogCategory
    {
        return $this->blogCategoryRepository->get($id, $blog);
    }

    /**
     * @param string $id
     * @param bool $permanent
     * @param Blog|null $blog
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function remove(string $id, bool $permanent = false, Blog $blog = null): void
    {
        $entity = $this->get($id, $blog);

        if ($permanent) {
            $this->entityManager->remove($entity);
        } else {
            $entity->remove();
        }

        $this->entityManager->flush();
    }
}