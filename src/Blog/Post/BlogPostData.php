<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\Tests\ORM\Tools\Pagination\Category;
use Rixafy\Blog\Blog;
use Rixafy\Blog\Publisher\BlogPublisher;
use Rixafy\Blog\Tag\BlogTag;
use Rixafy\Image\Image;

class BlogPostData
{
	/** @var string */
	public $title;

	/** @var string */
	public $content;

	/** @var string */
	public $editorial;

	/** @var string */
	public $keywords;

	/** @var string */
	public $route;

	/** @var Image */
	public $previewImage;

	/** @var BlogTag[] */
	public $tags;

	/** @var Category */
	public $category;

	/** @var BlogPublisher */
	public $publisher;

	/** @var Blog */
	public $blog;
}
