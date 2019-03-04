<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPublisher;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Rixafy\Blog\Blog;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\PublishableTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_publisher", indexes={
 *     @ORM\UniqueConstraint(columns={"id", "blog_id"})
 * })
 */
class BlogPublisher
{
    use UniqueTrait;
    use PublishableTrait;
    use RemovableTrait;
    use DateTimeTrait;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $displayName;

    /**
     * @ORM\Column(type="text", length=1023)
     * @var string
     */
    private $signature;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $first_posted_at;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $last_posted_at;

    /**
     * Many BlogPublishers have One Blog
     *
     * @ORM\ManyToOne(targetEntity="\Rixafy\Blog\Blog")
     * @var Blog
     */
    private $blog;

    /**
     * @return Blog
     */
    public function getBlog(): Blog
    {
        return $this->blog;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return DateTime
     */
    public function getFirstPostedAt(): DateTime
    {
        return $this->first_posted_at;
    }

    /**
     * @return DateTime
     */
    public function getLastPostedAt(): DateTime
    {
        return $this->last_posted_at;
    }
}