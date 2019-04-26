<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

class BlogCategoryFactory
{
	public function create(BlogCategoryData $blogData): BlogCategory
	{
		return new BlogCategory($blogData);
	}
}
