<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Blog;
use Rixafy\Blog\Publisher\Exception\BlogPublisherNotFoundException;

class BlogPublisherRepository
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
        return $this->entityManager->getRepository(BlogPublisher::class);
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @return BlogPublisher
     * @throws BlogPublisherNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId = null): BlogPublisher
    {
        $blogPost = $this->find($id, $blogId);

        if ($blogPost === null) {
            throw BlogPublisherNotFoundException::byId($id);
        }

        return $blogPost;
    }

    public function find(UuidInterface $id, UuidInterface $blogId): ?BlogPublisher
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
            ->where('b.is_active = :active')->setParameter('active', true)
            ->orderBy('b.created_at');
    }
}