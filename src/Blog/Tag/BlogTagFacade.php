<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\BlogRepository;
use Rixafy\Blog\Exception\BlogNotFoundException;

class BlogTagFacade extends BlogTagRepository
{
	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var BlogRepository */
	private $blogRepository;

	/** @var BlogTagFactory */
	private $blogTagFactory;

	public function __construct(
		EntityManagerInterface $entityManager,
		BlogRepository $blogRepository,
		BlogTagFactory $blogTagFactory
	) {
		parent::__construct($entityManager);
		$this->blogRepository = $blogRepository;
		$this->entityManager = $entityManager;
		$this->blogTagFactory = $blogTagFactory;
	}

	/**
	 * @throws BlogNotFoundException
	 */
	public function create(UuidInterface $blogId, BlogTagData $blogTagData): BlogTag
	{
		$blog = $this->blogRepository->get($blogId);
		$tag = $blog->addTag($blogTagData, $this->blogTagFactory);

		$this->entityManager->persist($tag);
		$this->entityManager->flush();

		return $tag;
	}

	/**
	 * @throws Exception\BlogTagNotFoundException
	 */
	public function edit(UuidInterface $id, UuidInterface $blogId, BlogTagData $blogTagData): BlogTag
	{
		$tag = $this->get($id, $blogId);

		$tag->edit($blogTagData);
		$this->entityManager->flush();

		return $tag;
	}

	/**
	 * @throws Exception\BlogTagNotFoundException
	 */
	public function remove(UuidInterface $id, UuidInterface $blogId): void
	{
		$entity = $this->get($id, $blogId);

		$entity->remove();

		$this->entityManager->flush();
	}
}
