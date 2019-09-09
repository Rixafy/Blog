<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Category\BlogCategoryFactory;
use Rixafy\Blog\Tag\BlogTagFactory;
use Rixafy\Routing\Route\Group\RouteGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Category\BlogCategory;
use Rixafy\Blog\Category\BlogCategoryData;
use Rixafy\Blog\Post\BlogPost;
use Rixafy\Blog\Tag\BlogTag;
use Rixafy\Blog\Tag\BlogTagData;
use Rixafy\DoctrineTraits\DateTimeTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog")
 */
class Blog
{
	use DateTimeTrait;

	/**
	 * @var UuidInterface
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", unique=true)
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=127)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=127)
	 * @var string
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=1023)
	 * @var string
	 */
	private $description;

	/**
	 * @ORM\Column(type="string", length=127)
	 * @var string
	 */
	private $keywords;

	/**
	 * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Category\BlogCategory", mappedBy="blog", cascade={"persist", "remove"})
	 * @var BlogCategory[]
	 */
	private $categories;

	/**
	 * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Post\BlogPost", mappedBy="blog", cascade={"persist", "remove"})
	 * @var BlogPost[]
	 */
	private $posts;

	/**
	 * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Tag\BlogTag", mappedBy="blog", cascade={"persist", "remove"})
	 * @var BlogTag[]
	 */
	private $tags;

	/**
	 * @ORM\OneToOne(targetEntity="\Rixafy\Routing\Route\Group\RouteGroup", cascade={"persist", "remove"})
	 * @var RouteGroup
	 */
	private $blogCategoryRouteGroup;

	/**
	 * @ORM\OneToOne(targetEntity="\Rixafy\Routing\Route\Group\RouteGroup", cascade={"persist", "remove"})
	 * @var RouteGroup
	 */
	private $blogPostRouteGroup;

	/**
	 * @ORM\OneToOne(targetEntity="\Rixafy\Routing\Route\Group\RouteGroup", cascade={"persist", "remove"})
	 * @var RouteGroup
	 */
	private $blogTagRouteGroup;

	public function __construct(
		UuidInterface $id,
		BlogData $blogData,
		RouteGroup $blogCategoryRouteGroup,
		RouteGroup $blogPostRouteGroup,
		RouteGroup $blogTagRouteGroup
	) {
		$this->id = $id;
		$this->blogCategoryRouteGroup = $blogCategoryRouteGroup;
		$this->blogPostRouteGroup = $blogPostRouteGroup;
		$this->blogTagRouteGroup = $blogTagRouteGroup;

		$this->categories = new ArrayCollection();
		$this->posts = new ArrayCollection();
		$this->tags = new ArrayCollection();

		$this->edit($blogData);
	}

	public function edit(BlogData $data): void
	{
		$this->name = $data->name;
		$this->title = $data->title;
		$this->description = $data->description;
		$this->keywords = $data->keywords;
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getData(): BlogData
	{
		$data = new BlogData();
		$data->name = $this->name;
		$data->title = $this->title;
		$data->description = $this->description;
		$data->keywords = $this->keywords;

		return $data;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getKeywords(): string
	{
		return $this->keywords;
	}

	public function addCategory(BlogCategoryData $data, BlogCategoryFactory $blogCategoryFactory): BlogCategory
	{
		$data->blog = $this;
		$category = $blogCategoryFactory->create($data);

		$this->categories->add($category);

		return $category;
	}

	public function removeCategory(BlogCategory $blogCategory): bool
	{
		return $this->categories->removeElement($blogCategory);
	}

	public function removePost(BlogPost $blogPost): bool
	{
		return $this->posts->removeElement($blogPost);
	}

	public function addTag(BlogTagData $blogTagData, BlogTagFactory $blogTagFactory): BlogTag
	{
		$blogTagData->blog = $this;
		$blogTag = $blogTagFactory->create($blogTagData);

		$this->tags->add($blogTag);

		return $blogTag;
	}

	public function removeTag(BlogTag $blogTag): bool
	{
		return $this->tags->removeElement($blogTag);
	}

	public function getBlogCategoryRouteGroup(): RouteGroup
	{
		return $this->blogCategoryRouteGroup;
	}

	public function getBlogPostRouteGroup(): RouteGroup
	{
		return $this->blogPostRouteGroup;
	}

	public function getBlogTagRouteGroup(): RouteGroup
	{
		return $this->blogTagRouteGroup;
	}
}
