<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPublisher;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogRepository;

class BlogPublisherFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogPublisherRepository */
    private $blogPublisherRepository;

    /**
     * BlogPublisherFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param BlogRepository $blogRepository
     * @param BlogPublisherRepository $blogPublisherRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        BlogRepository $blogRepository,
        BlogPublisherRepository $blogPublisherRepository
    ) {
        $this->blogRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogPublisherRepository = $blogPublisherRepository;
    }

    /**
     * @param string $blogId
     * @param BlogPublisherData $blogPublisherData
     * @return BlogPublisher
     * @throws \Rixafy\Blog\Exception\BlogNotFoundException
     */
    public function create(string $blogId, BlogPublisherData $blogPublisherData): BlogPublisher
    {
        $blog = $this->blogRepository->get($blogId);
        $publisher = $blog->addPublisher($blogPublisherData);

        $this->entityManager->persist($publisher);
        $this->entityManager->flush();

        return $publisher;
    }

    /**
     * @param string $id
     * @param BlogPublisherData $blogPublisherData
     * @param Blog|null $blog
     * @return BlogPublisher
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function edit(string $id, BlogPublisherData $blogPublisherData, Blog $blog = null): BlogPublisher
    {
        $publisher = $this->blogPublisherRepository->get($id, $blog);
        $publisher->edit($blogPublisherData);

        $this->entityManager->flush();

        return $publisher;
    }

    /**
     * @param string $id
     * @param Blog|null $blog
     * @return BlogPublisher
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogPublisher
    {
        return $this->blogPublisherRepository->get($id, $blog);
    }

    /**
     * @param string $id
     * @param bool $permanent
     * @param Blog|null $blog
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function remove(string $id, bool $permanent = false, Blog $blog = null): void
    {
        $entity = $this->get($id, $blog);

        if ($permanent) {
            $this->entityManager->remove($entity);
        } else {
            $entity->remove();
        }

        $this->entityManager->flush();
    }
}