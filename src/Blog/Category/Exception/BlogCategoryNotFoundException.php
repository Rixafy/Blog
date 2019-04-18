<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category\Exception;

use Exception;
use Rixafy\Blog\Category\Constraint\BlogCategoryUniqueConstraint;

class BlogCategoryNotFoundException extends Exception
{
	public static function byId(BlogCategoryUniqueConstraint $id): self
	{
		return new self('BlogCategory with id "' . $id->getId() . '" and blog_id "' . $id->getBlogId() . '" not found.');
	}
}