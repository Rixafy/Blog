<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Blog;
use Rixafy\Blog\Publisher\Constraint\BlogPublisherUniqueConstraint;
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
     * @throws BlogPublisherNotFoundException
     */
    public function get(UuidInterface $id): BlogPublisher
    {
    	/** @var BlogPublisher $blogPublisher */
    	$blogPublisher = $this->getRepository()->find($id);

        if ($blogPublisher === null) {
            throw BlogPublisherNotFoundException::byId($id);
        }

        return $blogPublisher;
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('e')
            ->where('e.is_active = :active')->setParameter('active', true)
            ->orderBy('e.created_at');
    }
}