<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Route;
use Rixafy\Routing\Route\RouteData;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_tag", uniqueConstraints={
 *	 @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * }, indexes={
 *	 @ORM\Index(columns={"is_removed"})
 * })
 */
class BlogTag
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
	 * Many BlogTags have One Blog
	 *
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
	 * @var Blog
	 */
	private $blog;

	public function __construct(UuidInterface $id, BlogTagData $data)
	{
		$this->id = $id;

		$routeGroup = $data->blog->getBlogPostRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($data->name);
		$routeData->target = $this->id;
		$routeData->controller = 'BlogTag';

		$this->route = new Route($routeData);

		$this->blog = $data->blog;

		$this->edit($data);
	}

	public function edit(BlogTagData $data): void
	{
		$this->route->changeName(Strings::webalize($data->name));
		$this->name = $data->name;
		$this->description = $data->description;
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getData(): BlogTagData
	{
		$data = new BlogTagData();

		$data->name = $this->name;
		$data->description = $this->description;

		return $data;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}
}
