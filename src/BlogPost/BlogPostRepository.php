<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogPost\Exception\BlogPostNotFoundException;

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
     * @param string $id
     * @param Blog|null $blog
     * @return BlogPost
     * @throws BlogPostNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogPost
    {
        $BlogPost = $this->find($id, $blog);

        if ($BlogPost === null) {
            throw new BlogPostNotFoundException('BlogPost with id ' . $id . ' not found.');
        }

        return $BlogPost;
    }

    public function find(string $id, Blog $blog = null): ?BlogPost
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