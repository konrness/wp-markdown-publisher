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
use MarkdownPublisher\WordPress\Repository\Post as PostRepository;
use MarkdownPublisher\WordPress\Repository\Category as CategoryRepository;
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
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var PostRepository
     */
    protected $postRepository;


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
        $post->post_title    = $contentItem->getTitle();
        $post->post_type     = $contentItem->getType();
        $post->tags_input    = $contentItem->getTags();

        /**
         * Complex transformations
         */
        $this->transformAuthor($contentItem, $post);

        $this->transformCategories($contentItem, $post);

        $this->transformCreatedDate($contentItem, $post);

        /**
         * @todo This naive process assumes that the parent already exists.
         * Improve to understand dependencies and run in the correct order.
         */
        $this->transformParent($contentItem, $post);

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
     * @return AuthorRepository
     */
    public function getAuthorRepository()
    {
        if (!$this->authorRepository) {
            throw new \Exception("AuthorRepository not set");
        }
        return $this->authorRepository;
    }

    /**
     * @param CategoryRepository $categoryRepository
     * @return $this;
     */
    public function setCategoryRepository($categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        return $this;
    }

    /**
     * @return CategoryRepository
     */
    public function getCategoryRepository()
    {
        if (!$this->categoryRepository) {
            throw new \Exception("CategoryRepository not set");
        }
        return $this->categoryRepository;
    }

    /**
     * @param PostRepository $postRepository
     * @return $this;
     */
    public function setPostRepository($postRepository)
    {
        $this->postRepository = $postRepository;
        return $this;
    }

    /**
     * @return PostRepository
     */
    public function getPostRepository()
    {
        if (!$this->postRepository) {
            throw new \Exception("PostRepository not set");
        }
        return $this->postRepository;
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
            return;
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

    /**
     * @param ContentItem $contentItem
     * @param Post $post
     */
    protected function transformParent(ContentItem $contentItem, Post $post)
    {
        $parent = $contentItem->getParent();

        if (! $parent) {
            $this->getLogger()->info("No parent provided for content item: " . $contentItem->getSlug() . ". Leaving empty.");
            return;
        }

        $post->post_parent = $this->getPostRepository()->getPostIDBySlug($parent, $contentItem->getType());

        if (!$post->post_parent) {
            $this->getLogger()->warning("Parent not found with slug '" . $parent . "' in content item: " . $contentItem->getSlug());
        } else {
            $this->getLogger()->info("Parent '" . $parent . "' transformed to " . $post->post_parent);
        }
    }

    /**
     * @param ContentItem $contentItem
     * @param Post $post
     */
    protected function transformCategories(ContentItem $contentItem, Post $post)
    {
        $categories = $contentItem->getCategories();

        if (! $categories) {
            $this->getLogger()->info("No categories provided for content item: " . $contentItem->getSlug());
            return;
        }

        if (! is_array($categories)) {
            $this->getLogger()->warning("Invalid format for categories provided for content item: " . $contentItem->getSlug());
            return;
        }

        $categoryIds = array();
        foreach ($categories as $category) {
            $category = trim($category);

            // @todo Feature: create the category if not found
            $categoryId = $this->getCategoryRepository()->getCategoryIDByTitleOrSlug($category);

            if ($categoryId) {
                $categoryIds[] = $categoryId;
                $this->getLogger()->info("Category '$category' transformed to $categoryId");
            } else {
                $this->getLogger()->warning("Category not found with slug/title '" . $category . "' in content item: " . $contentItem->getSlug());

            }
        }

    }

    /**
     * @param ContentItem $contentItem
     * @param Post $post
     */
    protected function transformCreatedDate(ContentItem $contentItem, Post $post)
    {
        /** @var int $created in timestamp format due to yaml parsing */
        $created = $contentItem->getCreated();

        if (! $created) {
            $this->getLogger()->warning("No created date provided for content item, setting to now.");
            $created = time();
        }

        if (!is_numeric($created)) {
            $this->getLogger()->warning("Invalid format '$created' for created provided for content item: " . $contentItem->getSlug() . ". Expecting Symfony/YAML parseable date/time. Setting to now.");
            $created = time();
        }

        $post->post_date = date("Y-m-d H:i:s", $created);

        $this->getLogger()->info("Created timestamp '$created' transformed to " . $post->post_date);
    }

} 