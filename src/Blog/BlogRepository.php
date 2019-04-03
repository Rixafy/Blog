<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
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
     * @param UuidInterface $id
     * @return Blog
     * @throws BlogNotFoundException
     */
    public function get(UuidInterface $id): Blog
    {
        /** @var Blog $blog */
        $blog = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($blog === null) {
            throw new BlogNotFoundException('Blog with id ' . $id . ' not found.');
        }

        return $blog;
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('b')
            ->where('b.is_active = :active')->setParameter('active', true)
            ->orderBy('b.created_at');
    }
}