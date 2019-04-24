<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\BlogRepository;
use Rixafy\Blog\Exception\BlogNotFoundException;

class BlogPublisherFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogPublisherRepository */
    private $blogPublisherRepository;

    /** @var BlogPublisherFactory */
    private $blogPublisherFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        BlogRepository $blogRepository,
        BlogPublisherRepository $blogPublisherRepository,
        BlogPublisherFactory $blogPublisherFactory
    ) {
        $this->blogRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogPublisherRepository = $blogPublisherRepository;
        $this->blogPublisherFactory = $blogPublisherFactory;
    }

    /**
     * @throws BlogNotFoundException
     */
    public function create(BlogPublisherData $blogPublisherData): BlogPublisher
    {
        $publisher = $this->blogPublisherFactory->create($blogPublisherData);

        $this->entityManager->persist($publisher);
        $this->entityManager->flush();

        return $publisher;
    }

    /**
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function edit(UuidInterface $id, BlogPublisherData $blogPublisherData): BlogPublisher
    {
        $publisher = $this->blogPublisherRepository->get($id);
        $publisher->edit($blogPublisherData);

        $this->entityManager->flush();

        return $publisher;
    }

    /**
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function get(UuidInterface $id): BlogPublisher
    {
        return $this->blogPublisherRepository->get($id);
    }

    /**
     * @throws Exception\BlogPublisherNotFoundException
     */
    public function remove(UuidInterface $id, bool $permanent = false): void
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