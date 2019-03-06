<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogPublisher\BlogPublisher;
use Rixafy\Blog\BlogTag\BlogTag;
use Rixafy\Doctrination\EntityTranslator;
use Rixafy\Doctrination\Language\Language;
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
     * Many BlogPosts have Many BlogTags
     * @ORM\ManyToMany(targetEntity="\Rixafy\BlogTag\BlogTag", inversedBy="blog_post", cascade={"persist", "remove"})
     * @var BlogTag[]
     */
    private $tags;

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
        if ($blogPostData->language !== null) {
            if ($this->fallback_language === null) {
                $this->addTranslation($blogPostData, $blogPostData->language);

            } else {
                $criteria = Criteria::create()
                    ->where(Criteria::expr()->eq('language', $blogPostData->language))
                    ->setMaxResults(1);

                /** @var BlogPostTranslation $translation */
                $translation = $this->translations->matching($criteria);

                if ($translation !== null) {
                    $translation->edit($blogPostData);

                } else {
                    $this->addTranslation($blogPostData, $blogPostData->language);
                }
            }
        }
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
     * @return BlogPostTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param BlogPostData $blogPostData
     * @param Language $language
     * @return BlogPostTranslation
     */
    public function addTranslation(BlogPostData $blogPostData, Language $language): BlogPostTranslation
    {
        $translation = new BlogPostTranslation($blogPostData, $language, $this);

        $this->translations->add($translation);

        if ($this->fallback_language === null) {
            $this->configureFallbackLanguage($language);
        }

        return $translation;
    }
}