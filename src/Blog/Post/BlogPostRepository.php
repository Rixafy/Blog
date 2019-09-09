<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Post\Exception\BlogPostNotFoundException;
use Rixafy\Language\LanguageProvider;

class BlogPostRepository
{
	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var LanguageProvider */
	private $languageProvider;

	public function __construct(EntityManagerInterface $entityManager, LanguageProvider $languageProvider)
	{
		$this->entityManager = $entityManager;
		$this->languageProvider = $languageProvider;
	}

    /**
     * @return EntityRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(BlogPost::class);
    }

    /**
     * @throws BlogPostNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogPost
    {
    	/** @var BlogPost $blogPost */
        $blogPost = $this->getRepository()->findOneBy([
        	'id' => $id,
			'blog' => $blogId
		]);

        if ($blogPost === null) {
            throw BlogPostNotFoundException::byId($id, $blogId);
        }

        return $blogPost;
    }

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->where('e.blog = :blog')->setParameter('blog', $blogId->getBytes())
			->andWhere('e.isRemoved = :removed')->setParameter('removed', false)
			->orderBy('e.createdAt');
	}
}
