<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Rixafy\Blog\Exception\BlogNotFoundException;

class BlogRepository
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
        return $this->entityManager->getRepository(Blog::class);
    }

    /**
     * @param string $id
     * @return Blog
     * @throws BlogNotFoundException
     */
    public function get(string $id): Blog
    {
        $blog = $this->find($id);

        if ($blog === null) {
            throw new BlogNotFoundException('Blog with id ' . $id . ' not found.');
        }

        return $blog;
    }

    public function find(string $id): ?Blog
    {
        return $this->getQueryBuilderForAll()
            ->andWhere('b.id = :id')->setParameter('id', Uuid::fromString($id))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('b')
            ->where('b.is_active = :active')->setParameter('active', true)
            ->orderBy('b.created_at');
    }
}