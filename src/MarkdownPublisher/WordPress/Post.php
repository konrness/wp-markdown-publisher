<?php

namespace MarkdownPublisher\WordPress;

/**
 * Class Post
 *
 * @author Konr Ness <konrness@gmail.com>
 * @see http://codex.wordpress.org/Function_Reference/wp_insert_post
 */
class Post
{

    /**
     * Not Implemented Yet
     *
    'guid',           // // Skip this and let Wordpress handle it, usually.
    'to_ping',        // // Space or carriage return-separated list of URLs to ping. Default empty string.
    'pinged',         // // Space or carriage return-separated list of URLs that have been pinged. Default empty string.
    'post_password',  // [ <string> ] // Password for post, if any. Default empty string.
    'post_content_filed', // // Skip this and let Wordpress handle it, usually.
    'comment_status', // [ 'closed' | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
    'post_date_gmt',  // [ Y-m-d H:i:s ] // The time post was made, in GMT.
    'tax_input'      => [ array( <taxonomy> => <array | string> ) ] // For custom taxonomies. Default empty.
     */
    public $ID;

    /**
     * The title of your post.
     *
     * @var string
     */
    public $page_title;

    /**
     * The name (slug) for your post
     *
     * @var string
     */
    public $post_name;

    /**
     * The full text of the post.
     *
     * @var string
     */
    public $post_content;

    /**
     * [ 'draft' | 'publish' | 'pending'| 'future' | 'public' | custom registered status ]
     * Default 'draft'.
     *
     * @var string
     */
    public $post_status;

    /**
     * [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ]
     * Default 'post'.
     *
     * @var string
     */
    public $post_type;

    /**
     * [ <user ID> ]
     * The user ID number of the author. Default is the current user ID.
     *
     * @var string
     */
    public $post_author;

    /**
     * [ 'closed' | 'open' ]
     * Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
     *
     * @var string
     */
    public $ping_status;

    /**
     * [ <post ID> ]
     * Sets the parent of the new post, if any. Default 0.
     *
     * @var string
     */
    public $post_parent;

    /**
     * [ <order> ]
     * If new post is a page, sets the order in which it should appear in supported menus. Default 0.
     *
     * @var string
     */
    public $menu_order;

    /**
     * For all your post excerpt needs.
     *
     * @var string
     */
    public $post_excerpt;

    /**
     * Format: 0000-00-00 00:00:00
     *
     * @todo Make sure that the file date format is parsed to full date/time format
     *
     * @var string
     */
    public $post_date;

    /**
     * [ array(<category id>, ...) ]
     *
     * @var string
     */
    public $post_category;

    /**
     * [ '<tag>, <tag>, ...' | array ]
     *
     * @var string
     */
    public $tags_input;

    /**
     * Requires name of template file, eg template.php. Default empty.
     *
     * @var string
     */
    public $page_template;

}
