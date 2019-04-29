<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Rixafy\Routing\Route\Group\RouteGroupData;
use Rixafy\Routing\Route\Group\RouteGroupFactory;

class BlogFactory
{
	/** @var RouteGroupFactory */
	private $routeGroupFactory;

	public function __construct(RouteGroupFactory $routeGroupFactory)
	{
		$this->routeGroupFactory = $routeGroupFactory;
	}

	public function create(BlogData $blogData): Blog
    {
    	$categoryGroup = $this->routeGroupFactory->create(new RouteGroupData());
    	$postGroup = $this->routeGroupFactory->create(new RouteGroupData());
    	$tagGroup = $this->routeGroupFactory->create(new RouteGroupData());

        return new Blog($blogData, $categoryGroup, $postGroup, $tagGroup);
    }
}
