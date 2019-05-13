<?php

declare(strict_types=1);

namespace Rixafy\Blog\Publisher;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Post\BlogPost;
use Rixafy\Blog\Post\BlogPostData;
use Rixafy\Blog\Post\BlogPostFactory;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\RemovableTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_publisher")
 */
class BlogPublisher
{
    use ActiveTrait;
    use RemovableTrait;
    use DateTimeTrait;

	/**
	 * @var UuidInterface
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", unique=true)
	 */
	protected $id;

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
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $firstPostedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $lastPostedAt;

    /**
     * One Blog has Many BlogPosts
     *
     * @ORM\OneToMany(targetEntity="\Rixafy\Blog\Post\BlogPost", mappedBy="blog_publisher", cascade={"persist", "remove"})
     * @var BlogPost[]
     */
    private $posts;

    public function __construct(UuidInterface $id, BlogPublisherData $blogPublisherData)
    {
    	$this->id = $id;
		$this->posts = new ArrayCollection();

		$this->edit($blogPublisherData);
    }

    public function edit(BlogPublisherData $blogPublisherData): void
    {
        $this->displayName = $blogPublisherData->displayName;
        $this->signature = $blogPublisherData->signature;
    }

	public function getId(): UuidInterface
	{
		return $this->id;
	}

    public function getData(): BlogPublisherData
	{
		$data = new BlogPublisherData();

		$data->displayName = $this->displayName;
		$data->signature = $this->signature;

		return $data;
	}

    public function publish(BlogPostData $blogPostData, BlogPostFactory $blogPostFactory): BlogPost
    {
    	$blogPostData->publisher = $this;
        $blogPost = $blogPostFactory->create($blogPostData);

        $this->posts->add($blogPost);

        if ($this->firstPostedAt === null) {
            $this->firstPostedAt = new DateTime();
        }

        $this->lastPostedAt = new DateTime();

        return $blogPost;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getFirstPostedAt(): DateTime
    {
        return $this->firstPostedAt;
    }

    public function getLastPostedAt(): DateTime
    {
        return $this->lastPostedAt;
    }
}
