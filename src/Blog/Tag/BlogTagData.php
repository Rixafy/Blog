<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Rixafy\Blog\Blog;

class BlogTagData
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $route;

    /** @var Blog */
    public $blog;
}
