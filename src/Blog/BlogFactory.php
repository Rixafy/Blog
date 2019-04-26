<?php

declare(strict_types=1);

namespace Rixafy\Blog;

class BlogFactory
{
    public function create(BlogData $blogData): Blog
    {
        return new Blog($blogData);
    }
}
