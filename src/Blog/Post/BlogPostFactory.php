<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Nette\Utils\Strings;
use Rixafy\Routing\Route\RouteData;
use Rixafy\Routing\Route\RouteGenerator;

class BlogPostFactory
{
	/** @var RouteGenerator */
	private $routeGenerator;

	public function __construct(RouteGenerator $routeGenerator)
	{
		$this->routeGenerator = $routeGenerator;
	}

	public function create(BlogPostData $blogPostData): BlogPost
	{
		$post = new BlogPost($blogPostData);

		$routeGroup = $blogPostData->blog->getBlogPostRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($blogPostData->name);
		$routeData->target = $post->getId();
		$routeData->language = $blogPostData->language;
		$routeData->controller = 'BlogPost';

		$this->routeGenerator->create($routeData);

		return $post;
	}
}
