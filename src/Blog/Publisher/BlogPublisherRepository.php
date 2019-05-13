<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
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
     * @return EntityRepository|ObjectRepository
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
            ->where('e.isActive = :active')->setParameter('active', true)
            ->orderBy('e.createdAt');
    }
}
