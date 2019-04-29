<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Strings;
use Rixafy\Routing\Route\RouteData;
use Rixafy\Routing\Route\RouteGenerator;

class BlogTagFactory
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

	public function create(BlogTagData $blogTagData): BlogTag
	{
		$tag = new BlogTag($blogTagData);
		$this->entityManager->persist($tag);

		$routeGroup = $blogTagData->blog->getBlogTagRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($blogTagData->name);
		$routeData->target = $tag->getId();
		$routeData->language = $blogTagData->language;
		$routeData->controller = 'BlogTag';

		$this->routeGenerator->create($routeData);

		return $tag;
	}
}
