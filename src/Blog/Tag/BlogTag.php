<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Translation\Annotation\Translatable;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Route;
use Rixafy\Routing\Route\RouteData;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\Translation\EntityTranslator;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_tag", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * })
 */
class BlogTag extends EntityTranslator
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
     * @Translatable
     * @var string
     */
    protected $name;

    /**
     * @Translatable
     * @var string
     */
    protected $description;

    /**
     * @Translatable
     * @var Route
     */
    protected $route;

    /**
     * Many BlogTags have One Blog
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
     * @var Blog
     */
    private $blog;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Tag\BlogTagTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogTagTranslation[]
     */
    protected $translations;

    public function __construct(UuidInterface $id, BlogTagData $data)
    {
    	$this->id = $id;

		$routeGroup = $data->blog->getBlogPostRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($data->name);
		$routeData->target = $this->id;
		$routeData->language = $data->language;
		$routeData->controller = 'BlogTag';

		$this->route = new Route($routeData);

        $this->translations = new ArrayCollection();
        $this->blog = $data->blog;

        $this->edit($data);
    }

    public function edit(BlogTagData $data): void
    {
		$data->route = $this->route;
		$data->route->changeName(Strings::webalize($data->name));
		$this->editTranslation($data);
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

    public function getBlog(): Blog
    {
        return $this->blog;
    }

    /**
     * @return BlogTagTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
