<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use Rixafy\Blog\Post\BlogPost;
use Rixafy\Blog\Post\BlogPostData;
use Rixafy\Blog\Post\BlogPostFactory;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\RemovableTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_publisher")
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
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Post\BlogPost", mappedBy="blog_publisher", cascade={"persist", "remove"})
     * @var BlogPost[]
     */
    private $posts;

    public function __construct(BlogPublisherData $blogPublisherData)
    {
        $this->edit($blogPublisherData);

        $this->posts = new ArrayCollection();
    }

    public function edit(BlogPublisherData $blogPublisherData): void
    {
        $this->display_name = $blogPublisherData->displayName;
        $this->signature = $blogPublisherData->signature;
    }

    public function getData(): BlogPublisherData
	{
		$data = new BlogPublisherData();

		$data->displayName = $this->display_name;
		$data->signature = $this->signature;

		return $data;
	}

    public function publish(BlogPostData $blogPostData, BlogPostFactory $blogPostFactory): BlogPost
    {
    	$blogPostData->publisher = $this;
        $blogPost = $blogPostFactory->create($blogPostData);

        $this->posts->add($blogPost);

        if ($this->first_posted_at === null) {
            $this->first_posted_at = new DateTime();
        }

        $this->last_posted_at = new DateTime();

        return $blogPost;
    }

    public function getDisplayName(): string
    {
        return $this->display_name;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getFirstPostedAt(): DateTime
    {
        return $this->first_posted_at;
    }

    public function getLastPostedAt(): DateTime
    {
        return $this->last_posted_at;
    }
}
