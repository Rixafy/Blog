<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Language\Language;

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
     * @ORM\Column(type="string", length=1023)
     * @var string
     */
    private $editorial;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $keywords;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $reading_time = 1;

    /**
     * Many Translations have One Language. Unidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Language\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var Language
     */
    private $language;

    /**
     * Many Translations have One Entity. Bidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Post\BlogPost", inversedBy="translations")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @var BlogPost
     */
    private $entity;

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
        $this->editorial = $blogPostData->editorial;
        $this->keywords = $blogPostData->keywords;
        $this->reading_time = floor(str_word_count(strip_tags($this->content)) / 200);
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

    public function getReadingTime(): float
    {
        return $this->reading_time;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}