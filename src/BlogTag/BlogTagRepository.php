<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogTag\Exception\BlogTagNotFoundException;

class BlogTagRepository
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
        return $this->entityManager->getRepository(BlogTag::class);
    }

    /**
     * @param string $id
     * @param Blog|null $blog
     * @return BlogTag
     * @throws BlogTagNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogTag
    {
        $blogTag = $this->find($id, $blog);

        if ($blogTag === null) {
            throw new BlogTagNotFoundException('BlogTag with id ' . $id . ' not found.');
        }

        return $blogTag;
    }

    public function find(string $id, Blog $blog = null): ?BlogTag
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