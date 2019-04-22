<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Category\Constraint\BlogCategoryUniqueConstraint;
use Rixafy\Blog\Category\Exception\BlogCategoryNotFoundException;
use Rixafy\Language\LanguageProvider;

class BlogCategoryRepository
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
        return $this->entityManager->getRepository(BlogCategory::class);
    }

    /**
     * @throws BlogCategoryNotFoundException
     */
    public function get(BlogCategoryUniqueConstraint $id): BlogCategory
    {
    	/** @var BlogCategory $blogCategory */
    	$blogCategory = $this->getRepository()->findOneBy([
    		'id' => $id->getId(),
			'blog' => $id->getBlogId()
		]);

        if ($blogCategory === null) {
            throw BlogCategoryNotFoundException::byId($id);
        }

        return $blogCategory;
    }

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->join(BlogCategoryTranslation::class, 'tr', Join::WITH,
				'tr.entity = e.id AND (tr.language = :currentLang OR tr.language = e.fallback_language)')
			->setParameter('currentLang', $this->languageProvider->getLanguage()->getId()->getBytes())
			->where('e.blog = :blog')->setParameter('blog', $blogId->getBytes())
			->andWhere('e.is_removed = :removed')->setParameter('removed', false)
			->orderBy('e.created_at');
	}
}