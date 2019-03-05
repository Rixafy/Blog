<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Blog\Blog;
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
     * @param Blog|null $blog
     * @return BlogPost
     * @throws Exception\BlogPostNotFoundException
     */
    public function edit(string $id, BlogPostData $blogPostData, Blog $blog = null): BlogPost
    {
        $post = $this->blogPostRepository->get($id, $blog);
        $post->edit($blogPostData);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param string $id
     * @param Blog|null $blog
     * @return BlogPost
     * @throws Exception\BlogPostNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogPost
    {
        return $this->blogPostRepository->get($id, $blog);
    }

    /**
     * @param string $id
     * @param bool $permanent
     * @param Blog|null $blog
     * @throws Exception\BlogPostNotFoundException
     */
    public function remove(string $id, bool $permanent = false, Blog $blog = null): void
    {
        $post = $this->get($id, $blog);

        if ($permanent) {
            $this->entityManager->remove($post);

        } else {
            $post->remove();
        }

        $this->entityManager->flush();
    }
}