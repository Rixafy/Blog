<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPublisher;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogPost\BlogPost;
use Rixafy\Blog\BlogPost\BlogPostData;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
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
    use ActiveTrait;
    use RemovableTrait;
    use DateTimeTrait;

    /**
     * @ORM\Column(type="string", length=127)
     * @var string
     */
    private $display_name;

    /**
     * @ORM\Column(type="text", length=1023)
     * @var string
     */
    private $signature;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $first_posted_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
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
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\BlogPost\BlogPost", mappedBy="blog_publisher", cascade={"persist", "remove"})
     * @var BlogPost[]
     */
    private $posts;

    public function __construct(BlogPublisherData $blogPublisherData, Blog $blog)
    {
        $this->blog = $blogPublisherData->blog;
        $this->edit($blogPublisherData);

        $this->posts = new ArrayCollection();
    }

    public function edit(BlogPublisherData $blogPublisherData)
    {
        $this->display_name = $blogPublisherData->displayName;
        $this->signature = $blogPublisherData->signature;
    }

    public function publish(BlogPostData $blogPostData): BlogPost
    {
        $blogPost = new BlogPost($blogPostData, $this);

        $this->posts->add($blogPost);

        return $blogPost;
    }

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
        return $this->display_name;
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