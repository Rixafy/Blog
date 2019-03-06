<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogCategory;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
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
 * @ORM\Table(name="blog_category", indexes={
 *     @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * })
 */
class BlogCategory extends EntityTranslator
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
     * Many BlogCategorys have One Blog
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
     * @var Blog
     */
    private $blog;

    /**
     * One Blog has Many Translations
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\BlogCategory\BlogCategoryTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogCategoryTranslation[]
     */
    private $translations;

    public function __construct(BlogCategoryData $blogCategoryData, Blog $blog)
    {
        $this->translations = new ArrayCollection();
        $this->blog = $blog;

        $this->edit($blogCategoryData);
    }

    public function edit(BlogCategoryData $blogCategoryData)
    {
        if ($blogCategoryData->language !== null) {
            if ($this->fallback_language === null) {
                $this->addTranslation($blogCategoryData, $blogCategoryData->language);

            } else {
                $criteria = Criteria::create()
                    ->where(Criteria::expr()->eq('language', $blogCategoryData->language))
                    ->setMaxResults(1);

                /** @var BlogCategoryTranslation $translation */
                $translation = $this->translations->matching($criteria);

                if ($translation !== null) {
                    $translation->edit($blogCategoryData);

                } else {
                    $this->addTranslation($blogCategoryData, $blogCategoryData->language);
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
     * @return BlogCategoryTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param BlogCategoryData $blogCategoryData
     * @param Language $language
     * @return BlogCategoryTranslation
     */
    public function addTranslation(BlogCategoryData $blogCategoryData, Language $language): BlogCategoryTranslation
    {
        $translation = new BlogCategoryTranslation($blogCategoryData, $language, $this);

        $this->translations->add($translation);

        if ($this->fallback_language === null) {
            $this->configureFallbackLanguage($language);
        }

        return $translation;
    }
}