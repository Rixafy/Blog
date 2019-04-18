<?php

declare(strict_types=1);

namespace Rixafy\Blog\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class BlogNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('Blog with id "' . $id . '" not found.');
	}
}