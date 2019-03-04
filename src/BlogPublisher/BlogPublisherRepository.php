<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPublisher;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogPublisher\Exception\BlogPublisherNotFoundException;

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
     * @param string $id
     * @param Blog|null $blog
     * @return BlogPublisher
     * @throws BlogPublisherNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogPublisher
    {
        $blogPost = $this->find($id, $blog);

        if ($blogPost === null) {
            throw new BlogPublisherNotFoundException('BlogPublisher with id ' . $id . ' not found.');
        }

        return $blogPost;
    }

    public function find(string $id, Blog $blog = null): ?BlogPublisher
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
            ->where('b.is_active = :active')->setParameter('active', true)
            ->orderBy('b.created_at');
    }
}