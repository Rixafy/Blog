<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\Tests\ORM\Tools\Pagination\Category;
use Rixafy\Blog\Tag\BlogTag;
use Rixafy\Image\Image;
use Rixafy\Language\Language;

class BlogPostData
{
    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var string */
    public $editorial = '';

    /** @var string */
    public $keywords = '';

    /** @var Image */
    public $backdropImage;

    /** @var BlogTag[] */
    public $tags;

    /** @var Category */
    public $category;

    /** @var Language */
    public $language;
}