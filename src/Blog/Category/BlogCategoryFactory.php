<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Rixafy\Routing\Route\RouteGenerator;

class BlogCategoryFactory
{
	/** @var RouteGenerator */
	private $routeGenerator;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(
		RouteGenerator $routeGenerator,
		EntityManagerInterface $entityManager
	) {
		$this->routeGenerator = $routeGenerator;
		$this->entityManager = $entityManager;
	}

	public function create(BlogCategoryData $blogCategoryData): BlogCategory
	{
		return new BlogCategory($blogCategoryData);
	}
}
