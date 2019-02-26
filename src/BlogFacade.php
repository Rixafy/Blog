<?php

declare(strict_types=1);

namespace Rixafy\Blog;

class BlogFacade
{
    /** @var BlogRepository */
    private $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }
}