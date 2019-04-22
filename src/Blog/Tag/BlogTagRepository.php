<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Tag\Constraint\BlogTagUniqueConstraint;
use Rixafy\Blog\Tag\Exception\BlogTagNotFoundException;
use Rixafy\Language\LanguageProvider;

class BlogTagRepository
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
        return $this->entityManager->getRepository(BlogTag::class);
    }

    /**
     * @throws BlogTagNotFoundException
     */
    public function get(BlogTagUniqueConstraint $id): BlogTag
    {
    	/** @var BlogTag $blogTag */
    	$blogTag = $this->getRepository()->findOneBy([
    		'id' => $id->getId(),
			'blog' => $id->getBlogId()
		]);

        if ($blogTag === null) {
            throw BlogTagNotFoundException::byId($id);
        }

        return $blogTag;
    }

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->join(BlogTagTranslation::class, 'tr', Join::WITH,
				'tr.entity = e.id AND (tr.language = :currentLang OR tr.language = e.fallback_language)')
			->setParameter('currentLang', $this->languageProvider->getLanguage()->getId()->getBytes())
			->where('e.blog = :blog')->setParameter('blog', $blogId)
			->andWhere('e.is_removed = :removed')->setParameter('removed', false)
			->orderBy('e.created_at');
	}
}