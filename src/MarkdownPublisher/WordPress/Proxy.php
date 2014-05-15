<?php

namespace MarkdownPublisher\WordPress;

use Nerdery\WordPress\Proxy as NerderyProxy;

class Proxy extends NerderyProxy
{
    /**
     * Returns the user ID if the user exists or null if the user doesn't exist.
     *
     * @see http://codex.wordpress.org/Function_Reference/username_exists
     * @param string $username a string representing the username to check for existence.
     * @return int|null
     */
    public function userNameExists($username)
    {
        return username_exists($username);
    }

    /**
     * Retrieve the ID of a category from its name.
     *
     * @see http://codex.wordpress.org/Function_Reference/get_cat_ID
     * @param string $categoryName Default is 'General' and can be any category name.
     * @return int 0 if failure and ID of category on success.
     */
    public function getCategoryIDByName($categoryName)
    {
        return get_cat_ID($categoryName);
    }

    /**
     *
     *
     * @see http://codex.wordpress.org/Function_Reference/get_category_by_slug
     * @param $categorySlug
     * @return int|null
     */
    public function getCategoryIDBySlug($categorySlug)
    {
        $category = get_category_by_slug($categorySlug);

        if ($category) {
            return $category->term_id;
        }

        return null;
    }
} 