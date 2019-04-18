<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Tag\Constraint\BlogTagUniqueConstraint;
use Rixafy\Blog\Tag\Exception\BlogTagNotFoundException;

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
     * @throws BlogTagNotFoundException
     */
    public function get(BlogTagUniqueConstraint $id): BlogTag
    {
    	/** @var BlogTag $blogTag */
    	$blogTag = $this->getRepository()->findOneBy([
    		'id' => $id->getId(),
			'blog' => $id->getBlogId()
		]);

        if ($blogTag === null) {
            throw BlogTagNotFoundException::byId($id);
        }

        return $blogTag;
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('b')
            ->where('b.is_removed = :removed')->setParameter('removed', false)
            ->orderBy('b.created_at');
    }
}