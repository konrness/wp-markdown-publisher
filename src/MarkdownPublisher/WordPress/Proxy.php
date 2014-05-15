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
     * Create an array of posts based on a set of parameters
     *
     * @see http://codex.wordpress.org/Template_Tags/get_posts
     * @param array $args Search parameters for finding posts.
     * @return \WP_Post[] Array of WP Post objects, or empty array if none found.
     */
    public function getPosts($args)
    {
        return get_posts($args);
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

    /**
     * This function updates posts (and pages) in the database.
     * To work as expected, it is necessary to pass the ID of the post to be updated.
     *
     * @see http://codex.wordpress.org/Function_Reference/wp_update_post
     * @param WP_Post $post
     * @return int The value 0 on failure. The post ID on success.
     */
    public function updatePost($post)
    {
        return wp_update_post($post);
    }

    /**
     * This function inserts posts (and pages) in the database.
     * It sanitizes variables, does some checks, fills in missing variables like date/time, etc.
     *
     * @see http://codex.wordpress.org/Function_Reference/wp_insert_post
     * @param WP_Post $post
     * @return int The value 0 on failure. The post ID on success.
     */
    public function insertPost($post)
    {
        return wp_update_post($post);
    }
} 