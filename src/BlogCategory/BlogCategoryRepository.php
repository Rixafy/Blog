<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogCategory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogCategory\Exception\BlogCategoryNotFoundException;

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
     * @param string $id
     * @param Blog|null $blog
     * @return BlogCategory
     * @throws BlogCategoryNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogCategory
    {
        $blogCategory = $this->find($id, $blog);

        if ($blogCategory === null) {
            throw new BlogCategoryNotFoundException('BlogCategory with id ' . $id . ' not found.');
        }

        return $blogCategory;
    }

    public function find(string $id, Blog $blog = null): ?BlogCategory
    {
        $queryBuilder = $this->getQueryBuilderForAll()
            ->andWhere('b.id = :id')->setParameter('id', Uuid::fromString($id));

        if ($blog !== null) {
            $queryBuilder = $queryBuilder->andWhere('b.blog = :blog')->setParameter('blog', $blog);
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