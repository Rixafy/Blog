<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Ramsey\Uuid\Uuid;

class BlogCategoryFactory
{
	public function create(BlogCategoryData $blogCategoryData): BlogCategory
	{
		return new BlogCategory(Uuid::uuid4(), $blogCategoryData);
	}
}
