<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Rixafy\Doctrination\Language\Language;

class BlogFactory
{
    public function create(BlogData $blogData, Language $language): Blog
    {
        return new Blog($blogData, $language);
    }
}