<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Rixafy\Blog\Blog;
use Rixafy\Language\Language;

class BlogCategoryData
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $route;

    /** @var BlogCategory */
    public $parent;

    /** @var Blog */
    public $blog;

    /** @var Language */
    public $language;
}
