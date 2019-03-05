<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Rixafy\Blog\BlogPost\BlogPostRepository;
use Rixafy\Blog\BlogPublisher\BlogPublisherRepository;
use Rixafy\Blog\BlogTag\BlogTagRepository;

class BlogFacade
{
    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogPostRepository */
    private $blogPostRepository;

    /** @var BlogPublisherRepository */
    private $blogPublisherRepository;

    /** @var BlogTagRepository */
    private $blogTagRepository;

    /**
     * BlogFacade constructor.
     * @param BlogRepository $blogRepository
     * @param BlogPostRepository $blogPostRepository
     * @param BlogPublisherRepository $blogPublisherRepository
     * @param BlogTagRepository $blogTagRepository
     */
    public function __construct(BlogRepository $blogRepository, BlogPostRepository $blogPostRepository, BlogPublisherRepository $blogPublisherRepository, BlogTagRepository $blogTagRepository)
    {
        $this->blogRepository = $blogRepository;
        $this->blogPostRepository = $blogPostRepository;
        $this->blogPublisherRepository = $blogPublisherRepository;
        $this->blogTagRepository = $blogTagRepository;
    }
}