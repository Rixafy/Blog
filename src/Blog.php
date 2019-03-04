<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\BlogPost\BlogPost;
use Rixafy\Blog\BlogTag\BlogTag;
use Rixafy\Blog\BlogTag\BlogTagData;
use Rixafy\Doctrination\EntityTranslator;
use Rixafy\Doctrination\Language\Language;
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
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogPost\BlogPost", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogPost[]
     */
    private $posts;

    /**
     * One Blog has Many BlogTag
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogPost\BlogTag", mappedBy="blog", cascade={"persist", "remove"})
     * @var BlogTag[]
     */
    private $tags;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogTranslation[]
     */
    private $translations;

    public function __construct(BlogData $blogData)
    {
        $this->translations = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->tags = new ArrayCollection();

        $this->edit($blogData);
    }

    public function edit(BlogData $blogData)
    {
        if ($blogData->language !== null) {
            if ($this->fallback_language === null) {
                $this->addTranslation($blogData, $blogData->language);

            } else {
                $criteria = Criteria::create()
                    ->where(Criteria::expr()->eq('language', $blogData->language))
                    ->setMaxResults(1);

                /** @var BlogTranslation $translation */
                $translation = $this->translations->matching($criteria);

                if ($translation !== null) {
                    $translation->edit($blogData);

                } else {
                    $this->addTranslation($blogData, $blogData->language);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
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
     * @return BlogTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param BlogData $blogData
     * @param Language $language
     * @return BlogTranslation
     */
    public function addTranslation(BlogData $blogData, Language $language): BlogTranslation
    {
        $translation = new BlogTranslation($blogData, $language, $this);

        $this->translations->add($translation);

        if ($this->fallback_language === null) {
            $this->configureFallbackLanguage($language);
        }

        return $translation;
    }
}