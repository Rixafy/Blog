<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Blog;
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
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @return BlogCategory
     * @throws BlogCategoryNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogCategory
    {
        $blogCategory = $this->find($id, $blogId);

        if ($blogCategory === null) {
            throw BlogCategoryNotFoundException::byId($id);
        }

        return $blogCategory;
    }

    public function find(UuidInterface $id, UuidInterface $blogId): ?BlogCategory
    {
        $queryBuilder = $this->getQueryBuilderForAll()
            ->andWhere('b.id = :id')->setParameter('id', $id);

        if ($blogId !== null) {
            $queryBuilder = $queryBuilder->andWhere('b.blog = :blog')->setParameter('blog', $blogId);
        }

        return $queryBuilder->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('b')
            ->where('b.is_removed = :removed')->setParameter('removed', false)
            ->orderBy('b.created_at');
    }
}