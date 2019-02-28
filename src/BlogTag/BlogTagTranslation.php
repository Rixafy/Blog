<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Doctrination\Language\Language;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_tag_translation", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"entity_id", "language_id"})
 * })
 */
class BlogTagTranslation
{
    use UniqueTrait;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1023)
     * @var string
     */
    private $description;

    /**
     * Many Translations have One Language. Unidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Doctrination\Language\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var \Rixafy\Doctrination\Language\Language
     */
    private $language;

    /**
     * Many Translations have One Entity. Bidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\BlogTag\BlogTag", inversedBy="translations")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @var BlogTag
     */
    private $entity;

    /**
     * BlogTranslation constructor.
     * @param string $name
     * @param string $description
     * @param \Rixafy\Doctrination\Language\Language $language
     * @param BlogTag $entity
     */
    public function __construct(string $name, string $description, Language $language, BlogTag $entity)
    {
        $this->name = $name;
        $this->description = $description;
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