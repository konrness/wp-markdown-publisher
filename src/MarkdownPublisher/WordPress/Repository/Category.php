<?php

namespace MarkdownPublisher\WordPress\Repository;

class Category extends Repository
{
    /**
     * @param string $category
     * @return int|null
     */
    public function getCategoryIDByTitleOrSlug($category)
    {
        $categoryId = $this->getProxy()->getCategoryIDBySlug($category);

        if (! $categoryId) {
            $categoryId = $this->getProxy()->getCategoryIDByName($category);
        }

        return $categoryId ? $categoryId : null;
    }
}