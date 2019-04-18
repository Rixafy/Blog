<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag\Exception;

use Exception;
use Rixafy\Blog\Tag\Constraint\BlogTagUniqueConstraint;

class BlogTagNotFoundException extends Exception
{
	public static function byId(BlogTagUniqueConstraint $id): self
	{
		return new self('BlogTag with id "' . $id->getId() . '" and blog_id "' . $id->getBlogId() . '" not found.');
	}
}