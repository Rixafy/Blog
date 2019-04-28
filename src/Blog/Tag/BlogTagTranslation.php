<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Language\Language;

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
     * @ORM\Column(type="string", length=1023, nullable=true)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $route;

    /**
     * Many Translations have One Language. Unidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Language\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @var Language
     */
    private $language;

    /**
     * Many Translations have One Entity. Bidirectional.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Tag\BlogTag", inversedBy="translations")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @var BlogTag
     */
    private $entity;

    public function __construct(BlogTagData $blogTagData, Language $language, BlogTag $entity)
    {
        $this->language = $language;
        $this->entity = $entity;
        $this->name = $blogTagData->name;
        $this->description = $blogTagData->description;
        $this->route = $blogTagData->route;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

	public function getRoute(): string
	{
		return $this->route;
	}
}
