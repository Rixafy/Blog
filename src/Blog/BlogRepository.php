<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\Common\Persistence\ObjectRepository;
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
     * @return EntityRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Blog::class);
    }

    /**
     * @throws BlogNotFoundException
     */
    public function get(UuidInterface $id): Blog
    {
        /** @var Blog $blog */
        $blog = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($blog === null) {
            throw BlogNotFoundException::byId($id);
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