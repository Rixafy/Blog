<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Ramsey\Uuid\Uuid;

class BlogPostFactory
{
	public function create(BlogPostData $blogPostData): BlogPost
	{
		return new BlogPost(Uuid::uuid4(), $blogPostData);
	}
}
