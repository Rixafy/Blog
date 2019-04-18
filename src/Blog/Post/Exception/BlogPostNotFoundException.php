<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post\Exception;

use Exception;
use Rixafy\Blog\Post\Constraint\BlogPostUniqueConstraint;

class BlogPostNotFoundException extends Exception
{
	public static function byId(BlogPostUniqueConstraint $id): self
	{
		return new self('BlogPost with id "' . $id->getId() . '" and blog_id "' . $id->getBlogId() . '" not found.');
	}
}