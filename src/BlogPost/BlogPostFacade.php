<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Blog\BlogPublisher\BlogPublisherRepository;

class BlogPostFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogPublisherRepository */
    private $blogPublisherRepository;

    /** @var BlogPostRepository */
    private $blogPostRepository;

    /**
     * BlogPostFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param BlogPublisherRepository $blogRepository
     * @param BlogPostRepository $blogPostRepository
     */
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
     * @param string $publisherId
     * @param BlogPostData $blogPostData
     * @return BlogPost
     * @throws \Rixafy\Blog\BlogPublisher\Exception\BlogPublisherNotFoundException
     */
    public function create(string $publisherId, BlogPostData $blogPostData): BlogPost
    {
        $publisher = $this->blogPublisherRepository->get($publisherId);
        $post = $publisher->publish($blogPostData);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param string $id
     * @param BlogPostData $blogPostData
     * @return BlogPost
     * @throws Exception\BlogPostNotFoundException
     */
    public function edit(string $id, BlogPostData $blogPostData): BlogPost
    {
        $post = $this->blogPostRepository->get($id);
        $post->edit($blogPostData);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param string $id
     * @return BlogPost
     * @throws Exception\BlogPostNotFoundException
     */
    public function get(string $id): BlogPost
    {
        return $this->blogPostRepository->get($id);
    }

    /**
     * @param string $id
     * @param bool $permanent
     * @throws Exception\BlogPostNotFoundException
     */
    public function remove(string $id, bool $permanent = false): void
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