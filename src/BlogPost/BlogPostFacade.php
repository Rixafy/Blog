<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
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
     * @param UuidInterface $blogId
     * @param UuidInterface $publisherId
     * @param BlogPostData $blogPostData
     * @return BlogPost
     * @throws \Rixafy\Blog\BlogPublisher\Exception\BlogPublisherNotFoundException
     */
    public function create(UuidInterface $blogId, UuidInterface $publisherId, BlogPostData $blogPostData): BlogPost
    {
        $publisher = $this->blogPublisherRepository->get($publisherId, $blogId);
        $post = $publisher->publish($blogPostData);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param BlogPostData $blogPostData
     * @return BlogPost
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
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @return BlogPost
     * @throws Exception\BlogPostNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogPost
    {
        return $this->blogPostRepository->get($id, $blogId);
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param bool $permanent
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