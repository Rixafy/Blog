<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\Doctrination\EntityTranslator;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_tag", uniqueConstraints={
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
    protected $translations;

    public function __construct(BlogTagData $blogTagData, Blog $blog)
    {
        $this->translations = new ArrayCollection();
        $this->blog = $blog;

        $this->edit($blogTagData);
    }

    /**
     * @param BlogTagData $blogTagData
     */
    public function edit(BlogTagData $blogTagData)
    {
        $this->editTranslation($blogTagData, $blogTagData->language);
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
}