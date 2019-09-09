<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
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
     * @return EntityRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(BlogCategory::class);
    }

    /**
     * @throws BlogCategoryNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogCategory
    {
    	/** @var BlogCategory $blogCategory */
    	$blogCategory = $this->getRepository()->findOneBy([
    		'id' => $id,
			'blog' => $blogId
		]);

        if ($blogCategory === null) {
            throw BlogCategoryNotFoundException::byId($id, $blogId);
        }

        return $blogCategory;
    }

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->where('e.blog = :blog')->setParameter('blog', $blogId->getBytes())
			->andWhere('e.isRemoved = :removed')->setParameter('removed', false)
			->orderBy('e.createdAt');
	}
}
