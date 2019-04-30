<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Translation\Annotation\Translatable;
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
use Rixafy\Translation\EntityTranslator;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_category", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * })
 */
class BlogCategory extends EntityTranslator
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
     * Many BlogCategories have One Blog
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
     * @var Blog
     */
    private $blog;

    /**
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Post\BlogPost", mappedBy="category")
     * @var BlogPost[]
     */
    private $posts;

	/**
	 * Many BlogCategories have One Category
	 *
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Category\BlogCategory")
	 * @var BlogCategory
	 */
	private $parent;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Category\BlogCategoryTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogCategoryTranslation[]
     */
    protected $translations;

    public function __construct(UuidInterface $id, BlogCategoryData $data)
    {
    	$this->id = $id;

    	$routeGroup = $data->blog->getBlogCategoryRouteGroup();

		$routeData = new RouteData();
		$routeData->group = $routeGroup;
		$routeData->site = $routeGroup->getSite();
		$routeData->name = Strings::webalize($data->name);
		$routeData->target = $this->id;
		$routeData->language = $data->language;
		$routeData->controller = 'BlogCategory';

		$this->route = new Route($routeData);
        $this->translations = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->blog = $data->blog;

		$this->edit($data);
    }

    public function edit(BlogCategoryData $data): void
    {
		$data->route = $this->route;
		$data->route->changeName(Strings::webalize($data->name));
		$this->editTranslation($data);
        $this->parent = $data->parent;
    }

    public function getData(): BlogCategoryData
	{
		$data = new BlogCategoryData();

		$data->name = $this->name;
		$data->language = $this->translationLanguage;
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

    public function getBlog(): Blog
    {
        return $this->blog;
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

    /**
     * @return BlogCategoryTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

	public function getRoute(): string
	{
		return $this->route;
	}
}
