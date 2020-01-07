<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Tag\Exception\BlogTagNotFoundException;

abstract class BlogTagRepository
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
		return $this->entityManager->getRepository(BlogTag::class);
	}

	/**
	 * @throws BlogTagNotFoundException
	 */
	public function get(UuidInterface $id, UuidInterface $blogId): BlogTag
	{
		/** @var BlogTag $blogTag */
		$blogTag = $this->getRepository()->findOneBy([
			'id' => $id,
			'blog' => $blogId
		]);

		if ($blogTag === null) {
			throw BlogTagNotFoundException::byId($id, $blogId);
		}

		return $blogTag;
	}

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->where('e.blog = :blog')->setParameter('blog', $blogId->getBytes())
			->andWhere('e.isRemoved = :removed')->setParameter('removed', false)
			->orderBy('e.createdAt');
	}

	/**
	 * @return BlogTag[]
	 */
	public function getAll(UuidInterface $blogId): array
	{
		return $this->getQueryBuilderForAll($blogId)->getQuery()->execute();
	}
}
