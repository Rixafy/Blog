<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Doctrination\Language\Language;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_post_translation", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"entity_id", "language_id"})
 * })
 */
class BlogPostTranslation
{
    use UniqueTrait;

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
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $keywords;

    /**
     * Many Translations have One Language. Unidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Doctrination\Language\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var \Rixafy\Doctrination\Language\Language
     */
    private $language;

    /**
     * Many Translations have One Entity. Bidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\BlogPost\BlogPost", inversedBy="translations")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @var BlogPost
     */
    private $entity;

    /**
     * BlogTranslation constructor.
     * @param BlogPostData $blogPostData
     * @param \Rixafy\Doctrination\Language\Language $language
     * @param BlogPost $entity
     */
    public function __construct(BlogPostData $blogPostData, Language $language, BlogPost $entity)
    {
        $this->language = $language;
        $this->entity = $entity;
        $this->edit($blogPostData);
    }

    public function edit(BlogPostData $blogPostData): void
    {
        $this->title = $blogPostData->title;
        $this->content = $blogPostData->content;
        $this->keywords = $blogPostData->keywords;
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
     * @return \Rixafy\Doctrination\Language\Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
}