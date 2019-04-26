<?php

declare(strict_types=1);

namespace Rixafy\Blog\Category;

use Rixafy\Language\Language;

class BlogCategoryData
{
    /** @var string */
    public $name;

    /** @var string */
    public $description = '';

    /** @var BlogCategory */
    public $parent;

    /** @var Language */
    public $language;
}
