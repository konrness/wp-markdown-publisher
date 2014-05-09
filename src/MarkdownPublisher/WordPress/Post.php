<?php

namespace MarkdownPublisher\WordPress;

use Fabricius\Annotation as Fabricius;

/**
 * Class Post
 *
 * @author Konr Ness <konrness@gmail.com>
 * @see http://codex.wordpress.org/Function_Reference/wp_insert_post
 * @Fabricius\ContentItem(repositoryClass="Fabricius\Repository\Repository")
 */
class Post
{

    /**
     * Not Implemented Yet
     *
    'ID',             // [ <post id> ] // Are you updating an existing post?
    'guid',           // // Skip this and let Wordpress handle it, usually.
    'to_ping',        // // Space or carriage return-separated list of URLs to ping. Default empty string.
    'pinged',         // // Space or carriage return-separated list of URLs that have been pinged. Default empty string.
    'post_password',  // [ <string> ] // Password for post, if any. Default empty string.
    'post_content_filed', // // Skip this and let Wordpress handle it, usually.
    'comment_status', // [ 'closed' | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
    'post_date_gmt',  // [ Y-m-d H:i:s ] // The time post was made, in GMT.
    'tax_input'      => [ array( <taxonomy> => <array | string> ) ] // For custom taxonomies. Default empty.
     */

    /**
     * The title of your post.
     *
     * @var string
     * @Fabricius\Title
     */
    private $title;

    /**
     * The name (slug) for your post
     *
     * @var string
     * @Fabricius\Slug
     */
    private $slug;

    /**
     * The full text of the post.
     *
     * @var string
     * @Fabricius\Body
     */
    private $body;

    /**
     * [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ]
     * Default 'draft'.
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $status;

    /**
     * [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ]
     * Default 'post'.
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $type;

    /**
     * [ <user ID> ]
     * The user ID number of the author. Default is the current user ID.
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $author;

    /**
     * [ 'closed' | 'open' ]
     * Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $ping_status;

    /**
     * [ <post ID> ]
     * Sets the parent of the new post, if any. Default 0.
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $parent;

    /**
     * [ <order> ]
     * If new post is a page, sets the order in which it should appear in supported menus. Default 0.
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $menu_order;

    /**
     * For all your post excerpt needs.
     *
     * @var string
     * @Fabricius\Excerpt
     */
    private $excerpt;

    /**
     * Format: 0000-00-00 00:00:00
     *
     * @todo Make sure that the file date format is parsed to full date/time format
     *
     * @var string
     * @Fabricius\Created
     */
    private $created;

    /**
     * [ array(<category id>, ...) ]
     *
     * @todo Parse this from comma-separated list of categories to array of category ids
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $categories;

    /**
     * [ '<tag>, <tag>, ...' | array ]
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $tags;

    /**
     * Requires name of template file, eg template.php. Default empty.
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $page_template;

    /**
     * @var string
     *
     * @Fabricius\Format
     */
    private $format;

    /**
     * Generates an array of data in proper format to be sent to wp_update_post()
     *
     * return array
     */
    public function toWpPost()
    {
        return array(
            //'ID'             => [ <post id> ] // Are you updating an existing post?
            'post_content'   => $this->body,
            'post_name'      => $this->slug,
            'post_title'     => $this->title,
            'post_status'    => $this->status,
            'post_type'      => $this->type,
            'post_author'    => $this->author, // @todo translate
            'ping_status'    => $this->ping_status,
            'post_parent'    => $this->parent,
            'menu_order'     => $this->menu_order,
            'post_excerpt'   => $this->excerpt,
            'post_date'      => $this->created,
            'post_category'  => $this->categories, // @todo translate
            'tags_input'     => $this->tags,
            'page_template'  => $this->page_template,
        );
    }

}
