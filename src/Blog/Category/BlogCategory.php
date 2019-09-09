<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Route;
use Rixafy\Routing\Route\RouteData;
use Rixafy\DoctrineTraits\SortOrderTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\Blog\Post\BlogPost;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_category", uniqueConstraints={
 *	 @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * }, indexes={
 *	 @ORM\Index(columns={"is_removed"})
 * })
 */
class BlogCategory
{
	use PublishableTrait;
	use RemovableTrait;
	use SortOrderTrait;
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
	 * @ORM\Column(type="string", length=1023, nullable=true)
	 * @var string
	 */
	private $description;

	/**
	 * @ORM\OneToOne(targetEntity="\Rixafy\Routing\Route\Route", cascade={"persist", "remove"})
	 * @var Route
	 */
	private $route;

	/**
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
	 * @var Blog
	 */
	private $blog;

	/**
	 * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Post\BlogPost", mappedBy="category")
	 * @var BlogPost[]
	 */
	private $posts;

	/**
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Category\BlogCategory")
	 * @var BlogCategory
	 */
	private $parent;

	public function __construct(UuidInterface $id, BlogCategoryData $data)
	{
		$this->id = $id;

		$routeGroup = $data->blog->getBlogCategoryRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($data->name);
		$routeData->target = $this->id;
		$routeData->controller = 'BlogCategory';

		$this->route = new Route($routeData);
		$this->posts = new ArrayCollection();
		$this->blog = $data->blog;

		$this->edit($data);
	}

	public function edit(BlogCategoryData $data): void
	{
		$this->route->changeName(Strings::webalize($data->name));
		$this->parent = $data->parent;
		$this->name = $data->name;
		$this->description = $data->description;
	}

	public function getData(): BlogCategoryData
	{
		$data = new BlogCategoryData();
		$data->name = $this->name;
		$data->description = $this->description;
		$data->parent = $this->parent;

		return $data;
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	/**
	 * @return BlogPost[]
	 */
	public function getPosts(): array
	{
		return $this->posts;
	}

	public function addPost(BlogPost $blogPost): void
	{
		$this->posts->add($blogPost);
	}

	public function getParent(): ?BlogCategory
	{
		return $this->parent;
	}
}
