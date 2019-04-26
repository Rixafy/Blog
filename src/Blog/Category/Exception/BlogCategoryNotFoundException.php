<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class BlogCategoryNotFoundException extends Exception
{
	public static function byId(UuidInterface $id, UuidInterface $blogId): self
	{
		return new self('BlogCategory with id "' . $id . '" and blog_id "' . $blogId . '" not found.');
	}
}
