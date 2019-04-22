<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Post\Constraint\BlogPostUniqueConstraint;
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
     * @return EntityRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(BlogPost::class);
    }

    /**
     * @throws BlogPostNotFoundException
     */
    public function get(BlogPostUniqueConstraint $id): BlogPost
    {
    	/** @var BlogPost $blogPost */
        $blogPost = $this->getRepository()->findOneBy([
        	'id' => $id->getId(),
			'blog' => $id->getBlogId()
		]);

        if ($blogPost === null) {
            throw BlogPostNotFoundException::byId($id);
        }

        return $blogPost;
    }

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->join(BlogPostTranslation::class, 'tr', Join::WITH,
				'tr.entity = e.id AND (tr.language = :currentLang OR tr.language = e.fallback_language)')
			->setParameter('currentLang', $this->languageProvider->getLanguage()->getId()->getBytes())
			->where('e.blog = :blog')->setParameter('blog', $blogId)
			->andWhere('e.is_removed = :removed')->setParameter('removed', false)
			->orderBy('e.created_at');
	}
}