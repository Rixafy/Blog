<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Translation\Annotation\Translatable;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Category\BlogCategory;
use Rixafy\Blog\Category\BlogCategoryData;
use Rixafy\Blog\Post\BlogPost;
use Rixafy\Blog\Tag\BlogTag;
use Rixafy\Blog\Tag\BlogTagData;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Translation\EntityTranslator;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog")
 */
class Blog extends EntityTranslator
{
    use UniqueTrait;
    use ActiveTrait;
    use DateTimeTrait;

    /**
     * @Translatable
     * @var string
     */
    protected $name;

    /**
     * @Translatable
     * @var string
     */
    protected $title;

    /**
     * @Translatable
     * @var string
     */
    protected $description;

    /**
     * @Translatable
     * @var string
     */
    protected $keywords;

    /**
     * One Blog has Many BlogCategories
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Category\BlogCategory", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogCategory[]
     */
    private $categories;

    /**
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Post\BlogPost", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogPost[]
     */
    private $posts;

    /**
     * One Blog has Many BlogTags
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Tag\BlogTag", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogTag[]
     */
    private $tags;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogTranslation[]
     */
    protected $translations;

    public function __construct(BlogData $blogData)
    {
        $this->translations = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->tags = new ArrayCollection();

        $this->edit($blogData);
    }

    public function edit(BlogData $blogData): void
    {
        $this->editTranslation($blogData);
    }

    public function getData(): BlogData
	{
		$data = new BlogData();

		$data->name = $this->name;
		$data->description = $this->description;
		$data->title = $this->title;
		$data->keywords = $this->keywords;
		$data->language = $this->translationLanguage;

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

    public function addCategory(BlogCategoryData $blogCategoryData): BlogCategory
    {
        $category = new BlogCategory($blogCategoryData, $this);

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

    public function addTag(BlogTagData $blogTagData): BlogTag
    {
        $blogTag = new BlogTag($blogTagData, $this);

        $this->tags->add($blogTag);

        return $blogTag;
    }

    public function removeTag(BlogTag $blogTag): bool
    {
        return $this->tags->removeElement($blogTag);
    }

    /**
     * @return BlogTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
