<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\BlogRepository;
use Rixafy\Blog\Exception\BlogNotFoundException;

class BlogPublisherFacade extends BlogPublisherRepository
{
	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var BlogRepository */
	private $blogRepository;

	/** @var BlogPublisherFactory */
	private $blogPublisherFactory;

	public function __construct(
		EntityManagerInterface $entityManager,
		BlogRepository $blogRepository,
		BlogPublisherFactory $blogPublisherFactory
	) {
		parent::__construct($entityManager);
		$this->blogRepository = $blogRepository;
		$this->entityManager = $entityManager;
		$this->blogPublisherFactory = $blogPublisherFactory;
	}

	/**
	 * @throws BlogNotFoundException
	 */
	public function create(BlogPublisherData $blogPublisherData): BlogPublisher
	{
		$publisher = $this->blogPublisherFactory->create($blogPublisherData);

		$this->entityManager->persist($publisher);
		$this->entityManager->flush();

		return $publisher;
	}

	/**
	 * @throws Exception\BlogPublisherNotFoundException
	 */
	public function edit(UuidInterface $id, BlogPublisherData $blogPublisherData): BlogPublisher
	{
		$publisher = $this->get($id);

		$publisher->edit($blogPublisherData);
		$this->entityManager->flush();

		return $publisher;
	}

	/**
	 * @throws Exception\BlogPublisherNotFoundException
	 */
	public function remove(UuidInterface $id): void
	{
		$entity = $this->get($id);

		$entity->remove();

		$this->entityManager->flush();
	}
}
