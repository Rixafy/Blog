<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Blog;
use Rixafy\Blog\Post\Exception\BlogPostNotFoundException;

class BlogPostRepository
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
        return $this->entityManager->getRepository(BlogPost::class);
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @return BlogPost
     * @throws BlogPostNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogPost
    {
        $blogPost = $this->find($id, $blogId);

        if ($blogPost === null) {
            throw new BlogPostNotFoundException('BlogPost with id ' . $id . ' not found.');
        }

        return $blogPost;
    }

    public function find(UuidInterface $id, UuidInterface $blogId): ?BlogPost
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