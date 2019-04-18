<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Rixafy\Blog\Category\Constraint\BlogCategoryUniqueConstraint;
use Rixafy\Blog\Category\Exception\BlogCategoryNotFoundException;

class BlogCategoryRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(BlogCategory::class);
    }

    /**
     * @throws BlogCategoryNotFoundException
     */
    public function get(BlogCategoryUniqueConstraint $id): BlogCategory
    {
    	/** @var BlogCategory $blogCategory */
    	$blogCategory = $this->getRepository()->findOneBy([
    		'id' => $id->getId(),
			'blog' => $id->getBlogId()
		]);

        if ($blogCategory === null) {
            throw BlogCategoryNotFoundException::byId($id);
        }

        return $blogCategory;
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('b')
            ->where('b.is_removed = :removed')->setParameter('removed', false)
            ->orderBy('b.created_at');
    }
}