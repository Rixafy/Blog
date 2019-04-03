<?php

declare(strict_types=1);

namespace Rixafy\Blog;

use Rixafy\Doctrination\Language\Language;

class BlogData
{
    /** @var string */
    public $name;

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var string */
    public $keywords;

    /** @var Language */
    public $language;
}