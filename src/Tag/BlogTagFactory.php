<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Ramsey\Uuid\Uuid;

class BlogTagFactory
{
	public function create(BlogTagData $blogTagData): BlogTag
	{
		return new BlogTag(Uuid::uuid4(), $blogTagData);
	}
}
