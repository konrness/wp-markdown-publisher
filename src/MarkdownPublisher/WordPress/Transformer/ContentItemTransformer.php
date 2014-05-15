<?php
/**
 * Created by PhpStorm.
 * User: kness
 * Date: 5/15/14
 * Time: 10:45 AM
 */

namespace MarkdownPublisher\WordPress\Transformer;

use MarkdownPublisher\WordPress\Post;
use MarkdownPublisher\Content\ContentItem;
use MarkdownPublisher\WordPress\Repository\Author as AuthorRepository;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ContentItemTransformer
{
    /**
     * @var ContentItem
     */
    protected $contentItem;

    /**
     * @var AuthorRepository
     */
    protected $authorRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger = array();

    /**
     * @return Post
     */
    public function transform()
    {
        $contentItem = $this->getContentItem();
        $post        = new Post();

        $this->getLogger()->info("Transformer -- Starting transform of " . $contentItem->getTitle());

        /**
         * One-to-one
         */
        $post->post_content  = $contentItem->getBody();
        $post->post_excerpt  = $contentItem->getExcerpt();
        $post->menu_order    = $contentItem->getMenuOrder();
        $post->page_template = $contentItem->getPageTemplate();
        $post->ping_status   = $contentItem->getPingStatus();
        $post->post_name     = $contentItem->getSlug();
        $post->post_status   = $contentItem->getStatus();
        $post->page_title    = $contentItem->getTitle();
        $post->post_type     = $contentItem->getType();

        /**
         * Complex transformations
         */
        $this->transformAuthor($contentItem, $post);

        /*
        $post->categories

        $post->created

        $post->parent

        $post->tags
        */

        return $post;

    }

    /**
     * @param AuthorRepository $authorRepository
     * @return $this;
     */
    public function setAuthorRepository(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
        return $this;
    }

    /**
     * @return \MarkdownPublisher\WordPress\Repository\Author
     */
    public function getAuthorRepository()
    {
        if (!$this->authorRepository) {
            throw new \Exception("AuthorRepository not set");
        }
        return $this->authorRepository;
    }

    /**
     * @param ContentItem $contentItem
     * @return $this;
     */
    public function setContentItem(ContentItem $contentItem)
    {
        $this->contentItem = $contentItem;
        return $this;
    }

    /**
     * @return \MarkdownPublisher\Content\ContentItem
     */
    public function getContentItem()
    {
        if (!$this->contentItem) {
            throw new \Exception("ContentItem not set");
        }
        return $this->contentItem;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @return $this;
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        if (!$this->logger) {
            $this->logger = new NullLogger();
        }
        return $this->logger;
    }

    /**
     * @param ContentItem $contentItem
     * @param Post $post
     */
    protected function transformAuthor(ContentItem $contentItem, Post $post)
    {
        if (! $contentItem->getAuthor()) {
            $this->getLogger()->notice("No author provided for content item: " . $contentItem->getSlug());
        }

        $post->post_author = $this->getAuthorRepository()->getAuthorIDByUsername(
            $contentItem->getAuthor()
        );

        if (!$post->post_author) {
            $this->getLogger()->warning("Author not found with username '" . $contentItem->getAuthor() . "' in content item: " . $contentItem->getSlug());
        } else {
            $this->getLogger()->info("Author '" . $contentItem->getAuthor() . "' transformed to " . $post->post_author);
        }
    }

} 