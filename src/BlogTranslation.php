<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Doctrination\Language\Language;
use Rixafy\DoctrineTraits\UniqueTrait;

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
     * @ORM\ManyToOne(targetEntity="\Rixafy\Doctrination\Language\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var \Rixafy\Doctrination\Language\Language
     */
    private $language;

    /**
     * Many Translations have One Entity. Bidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog", inversedBy="translations")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @var Blog
     */
    private $entity;

    /**
     * BlogTranslation constructor.
     * @param string $name
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param \Rixafy\Doctrination\Language\Language $language
     * @param Blog $entity
     */
    public function __construct(string $name, string $title, string $description, string $keywords, Language $language, Blog $entity)
    {
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->language = $language;
        $this->entity = $entity;
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
     * @return \Rixafy\Doctrination\Language\Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param \Rixafy\Doctrination\Language\Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }
}