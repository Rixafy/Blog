<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Publisher\BlogPublisherRepository;
use Rixafy\Blog\Publisher\Exception\BlogPublisherNotFoundException;

class BlogPostFacade extends BlogPostRepository
{
	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var BlogPublisherRepository */
	private $blogPublisherRepository;

	/** @var BlogPostFactory */
	private $blogPostFactory;

	public function __construct(
		EntityManagerInterface $entityManager,
		BlogPublisherRepository $blogRepository,
		BlogPostFactory $blogPostFactory
	) {
		parent::__construct($entityManager);
		$this->blogPublisherRepository = $blogRepository;
		$this->entityManager = $entityManager;
		$this->blogPostFactory = $blogPostFactory;
	}

	/**
	 * @throws BlogPublisherNotFoundException
	 */
	public function create(BlogPostData $blogPostData): BlogPost
	{
		$post = $blogPostData->publisher->publish($blogPostData, $this->blogPostFactory);

		$this->entityManager->persist($post);
		$this->entityManager->flush();

		return $post;
	}

	/**
	 * @throws Exception\BlogPostNotFoundException
	 */
	public function edit(UuidInterface $id, UuidInterface $blogId, BlogPostData $blogPostData): BlogPost
	{
		$post = $this->get($id, $blogId);

		$post->edit($blogPostData);
		$this->entityManager->flush();

		return $post;
	}

	/**
	 * @throws Exception\BlogPostNotFoundException
	 */
	public function remove(UuidInterface $id, UuidInterface $blogId): void
	{
		$post = $this->get($id, $blogId);

		$post->remove();

		$this->entityManager->flush();
	}
}
