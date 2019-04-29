<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Strings;
use Rixafy\Routing\Route\RouteData;
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
		$category = new BlogCategory($blogCategoryData);
		$this->entityManager->persist($category);

		$routeGroup = $blogCategoryData->blog->getBlogCategoryRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($blogCategoryData->name);
		$routeData->target = $category->getId();
		$routeData->language = $blogCategoryData->language;
		$routeData->controller = 'BlogCategory';

		$this->routeGenerator->create($routeData);

		return $category;
	}
}
