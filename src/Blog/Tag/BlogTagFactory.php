<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

class BlogTagFactory
{
	public function create(BlogTagData $data): BlogTag
	{
		return new BlogTag($data);
	}
}
