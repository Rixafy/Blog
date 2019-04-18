<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher\Exception;

use Exception;
use Rixafy\Blog\Publisher\Constraint\BlogPublisherUniqueConstraint;

class BlogPublisherNotFoundException extends Exception
{
	public static function byId(BlogPublisherUniqueConstraint $id): self
	{
		return new self('BlogPublisher with id "' . $id->getId() . '" and blog_id "' . $id->getBlogId() . '" not found.');
	}
}