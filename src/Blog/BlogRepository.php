<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Exception\BlogNotFoundException;
use Rixafy\Language\LanguageProvider;

class BlogRepository
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
        return $this->entityManager->getRepository(Blog::class);
    }

    /**
     * @throws BlogNotFoundException
     */
    public function get(UuidInterface $id): Blog
    {
        /** @var Blog $blog */
        $blog = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($blog === null) {
            throw BlogNotFoundException::byId($id);
        }

        return $blog;
    }

	public function getQueryBuilderForAll(): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->andWhere('e.is_active = :active')->setParameter('active', true)
			->orderBy('e.created_at');
	}
}
