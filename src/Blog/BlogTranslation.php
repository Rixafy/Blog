<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Language\Language;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_translation", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"entity_id", "language_id"})
 * })
 */
class BlogTranslation
{
    use UniqueTrait;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1023)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $keywords;

    /**
     * Many Translations have One Language. Unidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Language\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var Language
     */
    private $language;

    /**
     * Many Translations have One Entity. Bidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog", inversedBy="translations")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @var Blog
     */
    private $entity;

    public function __construct(BlogData $blogData, Language $language, Blog $entity)
    {
        $this->language = $language;
        $this->entity = $entity;
        $this->name = $blogData->name;
        $this->title = $blogData->title;
        $this->description = $blogData->description;
        $this->keywords = $blogData->keywords;
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

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
