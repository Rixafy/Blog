<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Doctrine\ORM\EntityManagerInterface;

class BlogRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}