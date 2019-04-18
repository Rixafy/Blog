<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class BlogTagNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('BlogTag with id "' . $id . '" not found.');
	}
}