<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;
use Rixafy\Translation\Annotation\Translatable;
use Doctrine\Common\Collections\ArrayCollection;
use Rixafy\Blog\Blog;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Translation\EntityTranslator;

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
     * @Translatable
     * @var string
     */
    protected $route;

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
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Tag\BlogTagTranslation", mappedBy="entity", cascade={"persist", "remove"})
     * @var BlogTagTranslation[]
     */
    protected $translations;

    public function __construct(BlogTagData $data)
    {
        $this->translations = new ArrayCollection();
        $this->blog = $data->blog;

        $this->edit($data);
    }

    public function edit(BlogTagData $data): void
    {
    	if ($data->route === null) {
    		$data->route = Strings::webalize($data->name);
		}
        $this->editTranslation($data, $data->language);
    }

    public function getData(): BlogTagData
	{
		$data = new BlogTagData();

		$data->name = $this->name;
		$data->description = $this->description;

		return $data;
	}

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

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

	public function getRoute(): string
	{
		return $this->route;
	}
}
