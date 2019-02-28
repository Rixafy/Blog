<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogTag;

use Rixafy\Blog\Blog;
use Rixafy\Doctrination\Language\Language;

class BlogTagData
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var Blog */
    public $blog;

    /** @var Language */
    public $language;
}