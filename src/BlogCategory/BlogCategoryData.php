<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogCategory;

use Rixafy\Doctrination\Language\Language;

class BlogCategoryData
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var Language */
    public $language;
}