<?php

declare(strict_types=1);

namespace Rixafy\Blog;

class BlogFacade
{
    /** @var BlogRepository */
    private $blogRepository;

    /** @var BlogDataFactory */
    private $blogDataFactory;

    /** @var BlogFactory */
    private $blogFactory;

    public function __construct(BlogRepository $blogRepository, BlogDataFactory $blogDataFactory, BlogFactory $blogFactory)
    {
        $this->blogRepository = $blogRepository;
        $this->blogDataFactory = $blogDataFactory;
        $this->blogFactory = $blogFactory;
    }
}