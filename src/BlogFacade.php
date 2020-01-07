<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Site\RouteSite;

class BlogFacade extends BlogRepository
{
	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var BlogFactory */
	private $blogFactory;

	public function __construct(
		EntityManagerInterface $entityManager,
		BlogFactory $blogFactory
	) {
		parent::__construct($entityManager);
		$this->entityManager = $entityManager;
		$this->blogFactory = $blogFactory;
	}

	public function create(BlogData $blogData, RouteSite $routeSite): Blog
	{
		$blog = $this->blogFactory->create($blogData, $routeSite);

		$this->entityManager->persist($blog);
		$this->entityManager->flush();

		return $blog;
	}

	/**
	 * @throws Exception\BlogNotFoundException
	 */
	public function edit(UuidInterface $id, BlogData $blogData): Blog
	{
		$blog = $this->get($id);

		$blog->edit($blogData);
		$this->entityManager->flush();

		return $blog;
	}
}
