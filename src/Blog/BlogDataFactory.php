<?php

declare(strict_types=1);

namespace Rixafy\Blog;

class BlogDataFactory
{
    public function create(string $name, string $title, string $description, string $keywords): BlogData
    {
        $blogData = new BlogData();

        $blogData->name = $name;
        $blogData->title = $title;
        $blogData->description = $description;
        $blogData->keywords = $keywords;

        return $blogData;
    }
}