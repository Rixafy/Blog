<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogCategory\BlogCategory;
use Rixafy\Blog\BlogPublisher\BlogPublisher;
use Rixafy\Blog\BlogTag\BlogTag;
use Rixafy\Doctrination\EntityTranslator;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_post", indexes={
 *     @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * })
 */
class BlogPost extends EntityTranslator
{
    use UniqueTrait;
    use PublishableTrait;
    use RemovableTrait;
    use DateTimeTrait;

    /**
     * @Translatable
     * @var string
     */
    protected $title;

    /**
     * @Translatable
     * @var string
     */
    protected $content;

    /**
     * @Translatable
     * @var string
     */
    protected $editorial;

    /**
     * @Translatable
     * @var string
     */
    protected $keywords;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $views = 0;

    /**
     * Many BlogPosts have One Blog
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
     * @var Blog
     */
    private $blog;

    /**
     * Many BlogPosts have One Publisher
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\BlogPublisher\BlogPublisher")
     * @var BlogPublisher
     */
    private $publisher;

    /**
     * Many BlogPosts have Many Tags
     * @ORM\ManyToMany(targetEntity="\Rixafy\BlogTag\BlogTag", inversedBy="blog_post", cascade={"persist", "remove"})
     * @var BlogTag[]
     */
    private $tags;

    /**
     * Many BlogPosts have One Category
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\BlogCategory\BlogCategory")
     * @var BlogCategory
     */
    private $category;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\BlogPost\BlogPostTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogPostTranslation[]
     */
    private $translations;

    public function __construct(BlogPostData $blogPostData, BlogPublisher $blogPublisher)
    {
        $this->translations = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->blog = $blogPublisher->getBlog();
        $this->publisher = $blogPublisher;

        $this->edit($blogPostData);
    }

    public function edit(BlogPostData $blogPostData)
    {
        $this->editTranslation($blogPostData);
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getEditorial(): string
    {
        return $this->editorial;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @return Blog
     */
    public function getBlog(): Blog
    {
        return $this->blog;
    }

    /**
     * @return BlogPublisher
     */
    public function getPublisher(): BlogPublisher
    {
        return $this->publisher;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    public function addView(): void
    {
        $this->views++;
    }

    /**
     * @param BlogTag $blogTag
     * @return bool
     */
    public function addTag(BlogTag $blogTag): bool
    {
        if (!$this->tags->contains($blogTag)) {
            return $this->tags->add($blogTag);
        }

        return false;
    }

    /**
     * @param BlogTag $blogTag
     * @return bool
     */
    public function removeTag(BlogTag $blogTag): bool
    {
        return $this->tags->removeElement($blogTag);
    }

    /**
     * @return BlogCategory
     */
    public function getCategory(): BlogCategory
    {
        return $this->category;
    }

    /**
     * @param BlogCategory $category
     */
    public function addToCategory(BlogCategory $category): void
    {
        $this->category = $category;
        $this->category->addPost($this);
    }

    /**
     * @return BlogPostTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}