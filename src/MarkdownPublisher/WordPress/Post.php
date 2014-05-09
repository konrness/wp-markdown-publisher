<?php

namespace MarkdownPublisher\WordPress;

use Fabricius\Annotation as Fabricius;

/**
 * Class Post
 *
 * @author Konr Ness <konrness@gmail.com>
 * @Fabricius\ContentItem(repositoryClass="Fabricius\Repository\Repository")
 */
class Post
{

    /**
     * @see http://codex.wordpress.org/Function_Reference/wp_insert_post
     */
    protected $fields = array(
        'ID',             // [ <post id> ] // Are you updating an existing post?
        'post_content',   // [ <string> ] // The full text of the post.
        'post_name',      // [ <string> ] // The name (slug) for your post
        'post_title',     // [ <string> ] // The title of your post.
        'post_status',    // [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
        'post_type',      // [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
        'post_author',    // [ <user ID> ] // The user ID number of the author. Default is the current user ID.
        'ping_status',    // [ 'closed' | 'open' ] // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
        'post_parent',    // [ <post ID> ] // Sets the parent of the new post, if any. Default 0.
        'menu_order',     // [ <order> ] // If new post is a page, sets the order in which it should appear in supported menus. Default 0.
        'to_ping',        // // Space or carriage return-separated list of URLs to ping. Default empty string.
        'pinged',         // // Space or carriage return-separated list of URLs that have been pinged. Default empty string.
        'post_password',  // [ <string> ] // Password for post, if any. Default empty string.
        'guid',           // // Skip this and let Wordpress handle it, usually.
        'post_content_filed', // // Skip this and let Wordpress handle it, usually.
        'post_excerpt',   // [ <string> ] // For all your post excerpt needs.
        'post_date',      // [ Y-m-d H:i:s ] // The time post was made.
        'post_date_gmt',  // [ Y-m-d H:i:s ] // The time post was made, in GMT.
        'comment_status', // [ 'closed' | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
        'post_category',  // [ array(<category id>, ...) ] // Default empty.
        'tags_input',     // [ '<tag>, <tag>, ...' | array ] // Default empty.
        'tax_input',      // [ array( <taxonomy> => <array | string> ) ] // For custom taxonomies. Default empty.
        'page_template',  // [ <string> ] // Requires name of template file, eg template.php. Default empty.
    );

    /**
     * @var string
     *
     * @Fabricius\Body
     */
    private $body;

    /**
     * @var array
     *
     * @Fabricius\Parameter
     */
    private $categories;

    /**
     * @var \DateTime
     *
     * @Fabricius\Created
     */
    private $created;

    /**
     * @var string
     *
     * @Fabricius\Excerpt
     */
    private $excerpt;

    /**
     * @var string
     *
     * @Fabricius\Format
     */
    private $format;

    /**
     * @var bool
     *
     * @Fabricius\Parameter
     */
    private $published;

    /**
     * @var string
     *
     * @Fabricius\Slug
     */
    private $slug;

    /**
     * @var array
     *
     * @Fabricius\Parameter
     */
    private $tags;

    /**
     * @var string
     *
     * @Fabricius\Title
     */
    private $title;

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set body
     *
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     *
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set format
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set published
     *
     * @param bool $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
