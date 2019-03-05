<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Doctrine\ORM\EntityManagerInterface;
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
     * @param string $blogId
     * @param BlogTagData $blogTagData
     * @return BlogTag
     * @throws \Rixafy\Blog\Exception\BlogNotFoundException
     */
    public function create(string $blogId, BlogTagData $blogTagData): BlogTag
    {
        $blog = $this->blogRepository->get($blogId);
        $tag = $blog->addTag($blogTagData);
        $this->entityManager->flush();

        return $tag;
    }

    /**
     * @param string $id
     * @param BlogTagData $blogTagData
     * @return BlogTag
     * @throws Exception\BlogTagNotFoundException
     */
    public function edit(string $id, BlogTagData $blogTagData): BlogTag
    {
        $tag = $this->blogTagRepository->get($id);
        $tag->edit($blogTagData);
        $this->entityManager->flush();

        return $tag;
    }

    /**
     * @param string $id
     * @return BlogTag
     * @throws Exception\BlogTagNotFoundException
     */
    public function get(string $id): BlogTag
    {
        return $this->blogTagRepository->get($id);
    }

    /**
     * @param string $id
     * @param bool $permanent
     * @throws Exception\BlogTagNotFoundException
     */
    public function remove(string $id, bool $permanent = false): void
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