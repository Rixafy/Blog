<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\BlogRepository;
use Rixafy\Blog\Exception\BlogNotFoundException;
use Rixafy\Blog\Publisher\Constraint\BlogPublisherUniqueConstraint;

class BlogPublisherFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogPublisherRepository */
    private $blogPublisherRepository;

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
     * @throws BlogNotFoundException
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
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function edit(BlogPublisherUniqueConstraint $id, BlogPublisherData $blogPublisherData): BlogPublisher
    {
        $publisher = $this->blogPublisherRepository->get($id);
        $publisher->edit($blogPublisherData);

        $this->entityManager->flush();

        return $publisher;
    }

    /**
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function get(BlogPublisherUniqueConstraint $id): BlogPublisher
    {
        return $this->blogPublisherRepository->get($id);
    }

    /**
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function remove(BlogPublisherUniqueConstraint $id, bool $permanent = false): void
    {
        $entity = $this->get($id);

        if ($permanent) {
            $this->entityManager->remove($entity);
        } else {
            $entity->remove();
        }

        $this->entityManager->flush();
    }
}