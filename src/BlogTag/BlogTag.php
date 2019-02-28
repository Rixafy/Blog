<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\Doctrination\EntityTranslator;
use Rixafy\Doctrination\Language\Language;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_tag", indexes={
 *     @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * })
 */
class BlogTag extends EntityTranslator
{
    use UniqueTrait;
    use PublishableTrait;
    use RemovableTrait;
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
    protected $description;

    /**
     * Many BlogTags have One Blog
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
     * @var Blog
     */
    private $blog;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\BlogTag\BlogTagTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogTagTranslation[]
     */
    private $translations;

    public function __construct(BlogTagData $blogTagData)
    {
        $this->name = $blogTagData->name;
        $this->description = $blogTagData->description;
        $this->blog = $blogTagData->blog;

        $this->translations = new ArrayCollection();

        $this->addTranslation($this->name, $this->description, $blogTagData->language);

        $this->configureFallbackLanguage($blogTagData->language);
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
     * @return Blog
     */
    public function getBlog(): Blog
    {
        return $this->blog;
    }

    /**
     * @return BlogTagTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param string $title
     * @param string $content
     * @param Language $language
     * @return BlogTagTranslation
     */
    public function addTranslation(string $title, string $content, Language $language): BlogTagTranslation
    {
        $translation = new BlogTagTranslation($title, $content, $language, $this);

        $this->translations->add($translation);

        return $translation;
    }
}