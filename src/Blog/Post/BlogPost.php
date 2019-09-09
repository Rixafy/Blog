<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Route;
use Rixafy\Routing\Route\RouteData;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\Blog\Category\BlogCategory;
use Rixafy\Blog\Publisher\BlogPublisher;
use Rixafy\Blog\Tag\BlogTag;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\Image\Image;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_post", uniqueConstraints={
 *	 @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * }, indexes={
 *	 @ORM\Index(columns={"is_removed"})
 * })
 */
class BlogPost
{
	use PublishableTrait;
	use RemovableTrait;
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
	private $title;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	private $content;

	/**
	 * @ORM\Column(type="string", length=1023, nullable=true)
	 * @var string
	 */
	private $editorial;

	/**
	 * @ORM\Column(type="string", length=127, nullable=true)
	 * @var string
	 */
	private $keywords;

	/**
	 * @ORM\OneToOne(targetEntity="\Rixafy\Routing\Route\Route", cascade={"persist", "remove"})
	 * @var Route
	 */
	private $route;

	/**
	 * @ORM\Column(type="float")
	 * @var float
	 */
	private $reading_time = 1;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $views = 0;

	/**
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
	 * @var Blog
	 */
	private $blog;

	/**
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Image\Image", cascade={"persist"})
	 * @var Image
	 */
	private $backdropImage;

	/**
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Publisher\BlogPublisher")
	 * @var BlogPublisher
	 */
	private $publisher;

	/**
	 * @ORM\ManyToMany(targetEntity="\Rixafy\Blog\Tag\BlogTag", inversedBy="blog_post", cascade={"persist", "remove"})
	 * @var BlogTag[]
	 */
	private $tags;

	/**
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Category\BlogCategory")
	 * @var BlogCategory
	 */
	private $category;

	public function __construct(UuidInterface $id, BlogPostData $data)
	{
		$this->id = $id;

		$routeGroup = $data->blog->getBlogPostRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($data->title);
		$routeData->target = $this->id;
		$routeData->controller = 'BlogPost';

		$this->route = new Route($routeData);

		$this->tags = new ArrayCollection();
		$this->blog = $data->blog;
		$this->publisher = $data->publisher;

		$this->edit($data);
	}

	public function edit(BlogPostData $data): void
	{
		$this->route->changeName(Strings::webalize($data->title));
		$this->backdropImage = $data->backdropImage;
		$this->category = $data->category;
		$this->tags = $data->tags;
		$this->title = $data->title;
		$this->content = $data->content;
		$this->editorial = $data->editorial;
		$this->keywords = $data->keywords;
	}

	public function getData(): BlogPostData
	{
		$data = new BlogPostData();
		$data->title = $this->title;
		$data->content = $this->content;
		$data->keywords = $this->keywords;
		$data->editorial = $this->editorial;
		$data->category = $this->category;
		$data->backdropImage = $this->backdropImage;
		$data->tags = $this->tags;

		return $data;
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function getEditorial(): ?string
	{
		return $this->editorial;
	}

	public function getKeywords(): ?string
	{
		return $this->keywords;
	}

	public function getBackdropImage(): ?Image
	{
		return $this->backdropImage;
	}

	public function getPublisher(): BlogPublisher
	{
		return $this->publisher;
	}

	public function getViews(): int
	{
		return $this->views;
	}

	public function addView(): void
	{
		$this->views++;
	}

	public function addTag(BlogTag $blogTag): bool
	{
		if (!$this->tags->contains($blogTag)) {
			return $this->tags->add($blogTag);
		}

		return false;
	}

	public function removeTag(BlogTag $blogTag): bool
	{
		return $this->tags->removeElement($blogTag);
	}

	public function getCategory(): BlogCategory
	{
		return $this->category;
	}

	public function addToCategory(BlogCategory $category): void
	{
		$this->category = $category;
		$this->category->addPost($this);
	}
}
