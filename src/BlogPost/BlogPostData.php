<?php

declare(strict_types=1);

namespace Rixafy\Blog\BlogPost;

use Rixafy\Blog\Blog;
use Rixafy\Blog\BlogPublisher\BlogPublisher;
use Rixafy\Doctrination\Language\Language;

class BlogPostData
{
    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var string */
    public $description;

    /** @var string */
    public $keywords;

    /** @var Blog */
    public $blog;

    /** @var BlogPublisher */
    public $blogPublisher;

    /** @var Language */
    public $language;
}