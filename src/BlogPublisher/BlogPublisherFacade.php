<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPublisher;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
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
     * @param UuidInterface $blogId
     * @param BlogPublisherData $blogPublisherData
     * @return BlogPublisher
     * @throws \Rixafy\Blog\Exception\BlogNotFoundException
     */
    public function create(UuidInterface $blogId, BlogPublisherData $blogPublisherData): BlogPublisher
    {
        $blog = $this->blogRepository->get($blogId);
        $publisher = $blog->addPublisher($blogPublisherData);

        $this->entityManager->persist($publisher);
        $this->entityManager->flush();

        return $publisher;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param BlogPublisherData $blogPublisherData
     * @return BlogPublisher
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function edit(UuidInterface $id, UuidInterface $blogId, BlogPublisherData $blogPublisherData): BlogPublisher
    {
        $publisher = $this->blogPublisherRepository->get($id, $blogId);
        $publisher->edit($blogPublisherData);

        $this->entityManager->flush();

        return $publisher;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @return BlogPublisher
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogPublisher
    {
        return $this->blogPublisherRepository->get($id, $blogId);
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param bool $permanent
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function remove(UuidInterface $id, UuidInterface $blogId, bool $permanent = false): void
    {
        $entity = $this->get($id, $blogId);

        if ($permanent) {
            $this->entityManager->remove($entity);
        } else {
            $entity->remove();
        }

        $this->entityManager->flush();
    }
}