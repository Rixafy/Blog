<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

class BlogPostFactory
{
    public function create(BlogPostData $blogPostData): BlogPost
    {
        return new BlogPost($blogPostData);
    }
}