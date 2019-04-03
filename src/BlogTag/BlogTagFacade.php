<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\BlogRepository;

class BlogTagFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogTagRepository */
    private $blogTagRepository;

    /**
     * BlogTagFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param BlogRepository $blogRepository
     * @param BlogTagRepository $blogTagRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        BlogRepository $blogRepository,
        BlogTagRepository $blogTagRepository
    ) {
        $this->blogRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogTagRepository = $blogTagRepository;
    }

    /**
     * @param UuidInterface $blogId
     * @param BlogTagData $blogTagData
     * @return BlogTag
     * @throws \Rixafy\Blog\Exception\BlogNotFoundException
     */
    public function create(UuidInterface $blogId, BlogTagData $blogTagData): BlogTag
    {
        $blog = $this->blogRepository->get($blogId);
        $tag = $blog->addTag($blogTagData);

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return $tag;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param BlogTagData $blogTagData
     * @return BlogTag
     * @throws Exception\BlogTagNotFoundException
     */
    public function edit(UuidInterface $id, UuidInterface $blogId, BlogTagData $blogTagData): BlogTag
    {
        $tag = $this->blogTagRepository->get($id, $blogId);
        $tag->edit($blogTagData);

        $this->entityManager->flush();

        return $tag;
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @return BlogTag
     * @throws Exception\BlogTagNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogTag
    {
        return $this->blogTagRepository->get($id, $blogId);
    }

    /**
     * @param UuidInterface $id
     * @param UuidInterface $blogId
     * @param bool $permanent
     * @throws Exception\BlogTagNotFoundException
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