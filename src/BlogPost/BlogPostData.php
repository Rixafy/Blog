<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Doctrine\Tests\ORM\Tools\Pagination\Category;
use Rixafy\Doctrination\Language\Language;
use Rixafy\Image\Image;

class BlogPostData
{
    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var string */
    public $editorial;

    /** @var string */
    public $description;

    /** @var string */
    public $keywords;

    /** @var Image */
    public $backdropImage;

    /** @var Category */
    public $category;

    /** @var Language */
    public $language;
}