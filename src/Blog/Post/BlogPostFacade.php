<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Blog\Post\Constraint\BlogPostUniqueConstraint;
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

    public function __construct(
        EntityManagerInterface $entityManager,
        BlogPublisherRepository $blogRepository,
        BlogPostRepository $blogPostRepository
    ) {
        $this->blogPublisherRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $blogPostRepository;
    }

    /**
     * @throws BlogPublisherNotFoundException
     */
    public function create(BlogPostData $blogPostData): BlogPost
    {
        $post = $blogPostData->publisher->publish($blogPostData);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function edit(BlogPostUniqueConstraint $id, BlogPostData $blogPostData): BlogPost
    {
        $post = $this->blogPostRepository->get($id);
        $post->edit($blogPostData);

        $this->entityManager->flush();

        return $post;
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function get(BlogPostUniqueConstraint $id): BlogPost
    {
        return $this->blogPostRepository->get($id);
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function remove(BlogPostUniqueConstraint $id, bool $permanent = false): void
    {
        $post = $this->get($id);

        if ($permanent) {
            $this->entityManager->remove($post);

        } else {
            $post->remove();
        }

        $this->entityManager->flush();
    }
}