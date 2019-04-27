<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Publisher\BlogPublisherRepository;
use Rixafy\Blog\Publisher\Exception\BlogPublisherNotFoundException;

class BlogPostFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogPublisherRepository */
    private $blogPublisherRepository;

    /** @var BlogPostRepository */
    private $blogPostRepository;

    /** @var BlogPostFactory */
    private $blogPostFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        BlogPublisherRepository $blogRepository,
        BlogPostRepository $blogPostRepository,
        BlogPostRepository $blogPostFactory
    ) {
        $this->blogPublisherRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $blogPostRepository;
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
        $post = $this->blogPostRepository->get($id, $blogId);
        $post->edit($blogPostData);

        $this->entityManager->flush();

        return $post;
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogPost
    {
        return $this->blogPostRepository->get($id, $blogId);
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function remove(UuidInterface $id, UuidInterface $blogId, bool $permanent = false): void
    {
        $post = $this->get($id, $blogId);

        if ($permanent) {
            $this->entityManager->remove($post);

        } else {
            $post->remove();
        }

        $this->entityManager->flush();
    }
}
