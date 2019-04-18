<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Rixafy\Blog\Post\Constraint\BlogPostUniqueConstraint;
use Rixafy\Blog\Post\Exception\BlogPostNotFoundException;

class BlogPostRepository
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
        return $this->entityManager->getRepository(BlogPost::class);
    }

    /**
     * @throws BlogPostNotFoundException
     */
    public function get(BlogPostUniqueConstraint $id): BlogPost
    {
    	/** @var BlogPost $blogPost */
        $blogPost = $this->getRepository()->findOneBy([
        	'id' => $id->getId(),
			'blog' => $id->getBlogId()
		]);

        if ($blogPost === null) {
            throw BlogPostNotFoundException::byId($id);
        }

        return $blogPost;
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('b')
            ->where('b.is_removed = :removed')->setParameter('removed', false)
            ->orderBy('b.created_at');
    }
}