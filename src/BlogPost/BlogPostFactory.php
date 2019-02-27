<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Rixafy\Doctrination\Language\Language;

class BlogPostFactory
{
    public function create(BlogPostData $blogPostData, Language $language): BlogPost
    {
        return new BlogPost($blogPostData, $language);
    }
}