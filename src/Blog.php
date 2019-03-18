<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\BlogCategory\BlogCategory;
use Rixafy\Blog\BlogCategory\BlogCategoryData;
use Rixafy\Blog\BlogPost\BlogPost;
use Rixafy\Blog\BlogPublisher\BlogPublisher;
use Rixafy\Blog\BlogPublisher\BlogPublisherData;
use Rixafy\Blog\BlogTag\BlogTag;
use Rixafy\Blog\BlogTag\BlogTagData;
use Rixafy\Doctrination\EntityTranslator;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

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
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogCategory\BlogCategory", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogCategory[]
     */
    private $categories;

    /**
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogPost\BlogPost", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogPost[]
     */
    private $posts;

    /**
     * One Blog has Many BlogTags
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogTag\BlogTag", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogTag[]
     */
    private $tags;

    /**
     * One Blog has Many BlogPublishers
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogPublisher\BlogPublisher", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogPublisher[]
     */
    private $publishers;

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
        $this->publishers = new ArrayCollection();

        $this->edit($blogData);
    }

    public function edit(BlogData $blogData)
    {
        $this->editTranslation($blogData);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * Add new blog category
     *
     * @param BlogCategoryData $blogCategoryData
     * @return BlogCategory
     */
    public function addCategory(BlogCategoryData $blogCategoryData): BlogCategory
    {
        $category = new BlogCategory($blogCategoryData, $this);

        $this->categories->add($category);

        return $category;
    }

    /**
     * Remove blog category
     *
     * @param BlogCategory $blogCategory
     * @return bool
     */
    public function removeCategory(BlogCategory $blogCategory): bool
    {
        return $this->categories->removeElement($blogCategory);
    }

    /**
     * Remove blog post
     *
     * @param BlogPost $blogPost
     * @return bool Successfully removed?
     */
    public function removePost(BlogPost $blogPost): bool
    {
        return $this->posts->removeElement($blogPost);
    }

    /**
     * Add new blog tag
     *
     * @param BlogTagData $blogTagData
     * @return BlogTag
     */
    public function addTag(BlogTagData $blogTagData): BlogTag
    {
        $blogTag = new BlogTag($blogTagData, $this);

        $this->tags->add($blogTag);

        return $blogTag;
    }

    /**
     * Remove blog tag
     *
     * @param BlogTag $blogTag
     * @return bool Successfully removed?
     */
    public function removeTag(BlogTag $blogTag): bool
    {
        return $this->tags->removeElement($blogTag);
    }

    /**
     * Add new blog publisher
     *
     * @param BlogPublisherData $blogPublisherData
     * @return BlogPublisher
     */
    public function addPublisher(BlogPublisherData $blogPublisherData): BlogPublisher
    {
        $publisher = new BlogPublisher($blogPublisherData, $this);

        $this->publishers->add($publisher);

        return $publisher;
    }

    /**
     * Remove blog publisher
     *
     * @param BlogPublisher $blogPublisher
     * @return bool Successfully removed?
     */
    public function removePublisher(BlogPublisher $blogPublisher): bool
    {
        return $this->publishers->removeElement($blogPublisher);
    }

    /**
     * @return BlogTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}