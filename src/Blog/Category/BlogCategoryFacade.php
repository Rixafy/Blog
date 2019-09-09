<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\BlogRepository;
use Rixafy\Blog\Exception\BlogNotFoundException;

class BlogCategoryFacade extends BlogCategoryRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogCategoryFactory */
    private $blogCategoryFactory;

    public function __construct(
		EntityManagerInterface $entityManager,
		BlogRepository $blogRepository,
		BlogCategoryFactory $blogCategoryFactory
	) {
    	parent::__construct($entityManager);
        $this->blogRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogCategoryFactory = $blogCategoryFactory;
	}

    /**
     * @throws BlogNotFoundException
     */
    public function create(UuidInterface $blogId, BlogCategoryData $blogCategoryData): BlogCategory
    {
        $blog = $this->blogRepository->get($blogId);
        $category = $blog->addCategory($blogCategoryData, $this->blogCategoryFactory);

		$this->entityManager->persist($category);
		$this->entityManager->flush();

        return $category;
    }

    /**
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function edit(UuidInterface $id, UuidInterface $blogId, BlogCategoryData $blogCategoryData): BlogCategory
    {
        $category = $this->get($id, $blogId);

        $category->edit($blogCategoryData);
		$this->entityManager->flush();

        return $category;
    }

    /**
     * @throws Exception\BlogCategoryNotFoundException
     */
    public function remove(UuidInterface $id, UuidInterface $blogId): void
    {
        $entity = $this->get($id, $blogId);

        $entity->remove();

        $this->entityManager->flush();
    }
}
