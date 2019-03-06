<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogCategory;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\Doctrination\Language\Language;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_category_translation", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"entity_id", "language_id"})
 * })
 */
class BlogCategoryTranslation
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
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\BlogCategory\BlogCategory", inversedBy="translations")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @var BlogCategory
     */
    private $entity;

    /**
     * BlogTranslation constructor.
     * @param BlogCategoryData $blogCategoryData
     * @param \Rixafy\Doctrination\Language\Language $language
     * @param BlogCategory $entity
     */
    public function __construct(BlogCategoryData $blogCategoryData, Language $language, BlogCategory $entity)
    {
        $this->language = $language;
        $this->entity = $entity;
        $this->edit($blogCategoryData);
    }

    public function edit(BlogCategoryData $blogCategoryData): void
    {
        $this->name = $blogCategoryData->name;
        $this->description = $blogCategoryData->description;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \Rixafy\Doctrination\Language\Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
}