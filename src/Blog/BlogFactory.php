<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Rixafy\Routing\Route\Group\RouteGroupData;
use Rixafy\Routing\Route\Group\RouteGroupFactory;
use Rixafy\Routing\Route\Site\RouteSite;

class BlogFactory
{
	/** @var RouteGroupFactory */
	private $routeGroupFactory;

	public function __construct(RouteGroupFactory $routeGroupFactory)
	{
		$this->routeGroupFactory = $routeGroupFactory;
	}

	public function create(BlogData $blogData, RouteSite $routeSite): Blog
    {
    	$categoryGroup = $this->routeGroupFactory->create(new RouteGroupData($routeSite));
    	$postGroup = $this->routeGroupFactory->create(new RouteGroupData($routeSite));
    	$tagGroup = $this->routeGroupFactory->create(new RouteGroupData($routeSite));

        return new Blog($blogData, $categoryGroup, $postGroup, $tagGroup);
    }
}
