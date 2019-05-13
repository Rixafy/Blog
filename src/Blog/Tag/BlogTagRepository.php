<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
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
     * @return EntityRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(BlogTag::class);
    }

    /**
     * @throws BlogTagNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogTag
    {
    	/** @var BlogTag $blogTag */
    	$blogTag = $this->getRepository()->findOneBy([
    		'id' => $id,
			'blog' => $blogId
		]);

        if ($blogTag === null) {
            throw BlogTagNotFoundException::byId($id, $blogId);
        }

        return $blogTag;
    }

	public function getQueryBuilderForAll(UuidInterface $blogId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->join(BlogTagTranslation::class, 'tr', Join::WITH,
				'tr.entity = e.id AND (tr.language = :currentLang OR tr.language = e.fallbackLanguage)')
			->setParameter('currentLang', $this->languageProvider->getLanguage()->getId()->getBytes())
			->where('e.blog = :blog')->setParameter('blog', $blogId->getBytes())
			->andWhere('e.isRemoved = :removed')->setParameter('removed', false)
			->orderBy('e.createdAt');
	}
}
