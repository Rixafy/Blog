<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Translation\Annotation\Translatable;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\Blog\Category\BlogCategory;
use Rixafy\Blog\Publisher\BlogPublisher;
use Rixafy\Blog\Tag\BlogTag;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Image\Image;
use Rixafy\Translation\EntityTranslator;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_post", uniqueConstraints={
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
     * Many BlogPosts have One Image
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Image\Image")
     * @var Image
     */
    private $backdrop_image;

    /**
     * Many BlogPosts have One Publisher
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Publisher\BlogPublisher")
     * @var BlogPublisher
     */
    private $publisher;

    /**
     * Many BlogPosts have Many Tags
     * @ORM\ManyToMany(targetEntity="\Rixafy\Blog\Tag\BlogTag", inversedBy="blog_post", cascade={"persist", "remove"})
     * @var BlogTag[]
     */
    private $tags;

    /**
     * Many BlogPosts have One Category
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Category\BlogCategory")
     * @var BlogCategory
     */
    private $category;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Post\BlogPostTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogPostTranslation[]
     */
    protected $translations;

    public function __construct(BlogPostData $blogPostData)
    {
        $this->translations = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->blog = $blogPostData->blog;
        $this->publisher = $blogPostData->publisher;

        $this->edit($blogPostData);
    }

    public function edit(BlogPostData $blogPostData): void
    {
        $this->editTranslation($blogPostData);
        $this->backdrop_image = $blogPostData->backdropImage;
        $this->category = $blogPostData->category;
        $this->tags = $blogPostData->tags;
    }

    public function getData(): BlogPostData
	{
		$data = new BlogPostData();

		$data->title = $this->title;
		$data->content = $this->content;
		$data->keywords = $this->keywords;
		$data->editorial = $this->editorial;
		$data->category = $this->category;
		$data->backdropImage = $this->backdrop_image;
		$data->tags = $this->tags;
		$data->language = $this->translationLanguage;

		return $data;
	}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getEditorial(): string
    {
        return $this->editorial;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function getBlog(): Blog
    {
        return $this->blog;
    }

    public function getBackdropImage(): Image
    {
        return $this->backdrop_image;
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

    /**
     * @return BlogPostTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
