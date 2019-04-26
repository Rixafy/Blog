<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

class BlogPublisherFactory
{
	public function create(BlogPublisherData $blogData): BlogPublisher
	{
		return new BlogPublisher($blogData);
	}
}
