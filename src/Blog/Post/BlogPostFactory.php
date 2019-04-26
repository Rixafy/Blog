<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

class BlogPostFactory
{
	public function create(BlogPostData $blogData): BlogPost
	{
		return new BlogPost($blogData);
	}
}
