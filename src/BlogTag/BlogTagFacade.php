<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Blog\Blog;
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

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return $tag;
    }

    /**
     * @param string $id
     * @param BlogTagData $blogTagData
     * @param Blog|null $blog
     * @return BlogTag
     * @throws Exception\BlogTagNotFoundException
     */
    public function edit(string $id, BlogTagData $blogTagData, Blog $blog = null): BlogTag
    {
        $tag = $this->blogTagRepository->get($id, $blog);
        $tag->edit($blogTagData);

        $this->entityManager->flush();

        return $tag;
    }

    /**
     * @param string $id
     * @param Blog|null $blog
     * @return BlogTag
     * @throws Exception\BlogTagNotFoundException
     */
    public function get(string $id, Blog $blog = null): BlogTag
    {
        return $this->blogTagRepository->get($id, $blog);
    }

    /**
     * @param string $id
     * @param bool $permanent
     * @param Blog|null $blog
     * @throws Exception\BlogTagNotFoundException
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