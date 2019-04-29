<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Strings;
use Rixafy\Routing\Route\RouteData;
use Rixafy\Routing\Route\RouteGenerator;

class BlogPostFactory
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

	public function create(BlogPostData $blogPostData): BlogPost
	{
		$post = new BlogPost($blogPostData);
		$this->entityManager->persist($post);

		$routeGroup = $blogPostData->blog->getBlogPostRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($blogPostData->title);
		$routeData->target = $post->getId();
		$routeData->language = $blogPostData->language;
		$routeData->controller = 'BlogPost';

		$this->routeGenerator->create($routeData);

		return $post;
	}
}
