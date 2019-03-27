<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
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
     * @param UuidInterface $blogId
     * @param UuidInterface $id
     * @return BlogTag
     * @throws BlogTagNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId = null): BlogTag
    {
        $blogTag = $this->find($id, $blogId);

        if ($blogTag === null) {
            throw new BlogTagNotFoundException('BlogTag with id ' . $id . ' not found.');
        }

        return $blogTag;
    }

    public function find(UuidInterface $id, UuidInterface $blogId): ?BlogTag
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