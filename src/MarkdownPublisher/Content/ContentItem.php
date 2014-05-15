<?php

namespace MarkdownPublisher\Content;

use Fabricius\Annotation as Fabricius;

/**
 * Parsed Content Item
 *
 * @author Konr Ness <konrness@gmail.com>
 * @Fabricius\ContentItem(repositoryClass="Fabricius\Repository\Repository")
 */
class ContentItem
{

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
     * [ <username> ]
     * The username of the author. Default is the current user.
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
     * [ Parent post slug ]
     * Sets the parent of the new post, if any. Default, none.
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
     * For all your post excerpt needs. Automatically set from post body with separator.
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
     * [ '<category_slug>, <category_slug>, ...' ]
     *
     * @todo Parse this from comma-separated list of categories to array of category ids
     *
     * @var string
     * @Fabricius\Parameter
     */
    private $categories;

    /**
     * [ '<tag>, <tag>, ...' ]
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
     * @param string $author
     * @return $this;
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $body
     * @return $this;
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $categories
     * @return $this;
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string $created
     * @return $this;
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $excerpt
     * @return $this;
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    /**
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @param string $format
     * @return $this;
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $menu_order
     * @return $this;
     */
    public function setMenuOrder($menu_order)
    {
        $this->menu_order = $menu_order;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenuOrder()
    {
        return $this->menu_order;
    }

    /**
     * @param string $page_template
     * @return $this;
     */
    public function setPageTemplate($page_template)
    {
        $this->page_template = $page_template;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageTemplate()
    {
        return $this->page_template;
    }

    /**
     * @param string $parent
     * @return $this;
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $ping_status
     * @return $this;
     */
    public function setPingStatus($ping_status)
    {
        $this->ping_status = $ping_status;
        return $this;
    }

    /**
     * @return string
     */
    public function getPingStatus()
    {
        return $this->ping_status;
    }

    /**
     * @param string $slug
     * @return $this;
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $status
     * @return $this;
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $tags
     * @return $this;
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $title
     * @return $this;
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $type
     * @return $this;
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    

} 