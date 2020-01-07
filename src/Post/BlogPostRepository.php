<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Post\Exception\BlogPostNotFoundException;

abstract class BlogPostRepository
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
		return $this->entityManager->getRepository(BlogPost::class);
	}

	/**
	 * @throws BlogPostNotFoundException
	 */
	public function get(UuidInterface $id, UuidInterface $blogId): BlogPost
	{
		/** @var BlogPost $blogPost */
		$blogPost = $this->getRepository()->findOneBy([
			'id' => $id,
			'blog' => $blogId
		]);

		if ($blogPost === null) {
			throw BlogPostNotFoundException::byId($id, $blogId);
		}

		return $blogPost;
	}

	public function getCount(UuidInterface $blogId): int
	{
		$qb = $this->getQueryBuilderForAll($blogId)
			->select('COUNT(e.id)');

		return (int) $qb->getQuery()
			->getSingleScalarResult();
	}

	public function getQueryBuilderForChunk(UuidInterface $blogId, int $offset, int $limit): QueryBuilder
	{
		return $this->getQueryBuilderForAll($blogId)
			->setMaxResults($limit)
			->setFirstResult($offset);
	}

	/**
	 * @return BlogPost[]
	 */
	public function getChunk(UuidInterface $blogId, int $offset, int $limit): array
	{
		return $this->getQueryBuilderForChunk($blogId, $offset, $limit)->getQuery()->execute();
	}

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->where('e.blog = :blog')->setParameter('blog', $blogId->getBytes())
			->andWhere('e.isRemoved = :removed')->setParameter('removed', false)
			->orderBy('e.createdAt');
	}

	/**
	 * @return BlogPost[]
	 */
	public function getAll(UuidInterface $blogId): array
	{
		return $this->getQueryBuilderForAll($blogId)->getQuery()->execute();
	}
}
