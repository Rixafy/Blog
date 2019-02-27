<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Rixafy\Blog\BlogPost\BlogPost;
use Rixafy\Blog\BlogPost\BlogPostData;
use Rixafy\Doctrination\Language\Language;

class BlogPostFactory
{
    public function create(BlogPostData $blogPostData, Language $language): BlogPost
    {
        return new BlogPost($blogPostData, $language);
    }
}