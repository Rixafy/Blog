<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class BlogPublisherNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('BlogPublisher with id "' . $id . '" not found.');
	}
}