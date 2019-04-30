<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Ramsey\Uuid\Uuid;

class BlogPublisherFactory
{
	public function create(BlogPublisherData $blogData): BlogPublisher
	{
		return new BlogPublisher(Uuid::uuid4(), $blogData);
	}
}
