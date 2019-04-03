<?php

declare(strict_types=1);

namespace Rixafy\Blog\Tag;

use Rixafy\Doctrination\Language\Language;

class BlogTagData
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var Language */
    public $language;
}