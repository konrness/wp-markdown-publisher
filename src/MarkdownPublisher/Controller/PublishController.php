<?php

namespace MarkdownPublisher\Controller;

use MarkdownPublisher\WordPress\Repository\Post;
use MarkdownPublisher\WordPress\Transformer\ContentItemTransformer;
use Nerdery\Plugin\Controller\Controller;
use MarkdownPublisher\Content\ContentItem;
use Fabricius\Library;
use Psr\Log\LoggerInterface;
use Monolog\Handler\TestHandler;

/**
 * Class PublishController
 *
 * @author Konr Ness <konrness@gmail.com>
 */
class PublishController extends Controller
{
    /*
     * Constants
     */
    const SLUG_PAGE_PUBLISH = 'mdpublisher_publish';

    /**
     * Register admin menu
     *
     * @return void
     */
    public function registerAdminRoutes()
    {
        /*
         * Alias $this as $controller to pass into the
         * closure, this is only necessary pre-5.4. Post 5.4 PHP properly
         * maintains the $this context within closures.
         */
        $controller = $this;
        $this->getProxy()->registerAdminRoute(
            self::SLUG_PAGE_PUBLISH,
            'edit_posts',
            function() use ($controller) {
                echo $controller->indexAction();
            }
        );
    }

    /**
     * Handle listing of participants
     *
     * @return string
     */
    public function indexAction()
    {
        $proxy = $this->getProxy();
        $container = $this->getContainer();

        /** @var Library $library */
        $library = $container['library'];

        /** @var LoggerInterface $logger */
        $logger = $container['logger'];

        $logger->info("In PublishController");

        // set timezone so yaml parsing can parse dates correctly
        $oldTimezone = date_default_timezone_get();
        date_default_timezone_set('America/Chicago');

        $contentItems = $library->getRepository('MarkdownPublisher\Content\ContentItem')->query();

        /** @var Post $postRepository */
        $postRepository = $container['repository.post'];

        // transform parsed data to WordPress posts
        $transformer = new ContentItemTransformer();

        $transformer->setLogger($logger);
        $transformer->setAuthorRepository($container['repository.author']);
        $transformer->setCategoryRepository($container['repository.category']);
        $transformer->setPostRepository($postRepository);

        $updatedContent = array();
        $insertedContent = array();
        $i = 0;
        foreach ($contentItems as $contentItem)
        {
            $logger->info("---------------------------");
            $logger->info("Starting content item #" . ++$i);
            $logger->info("---------------------------");

            /** @var ContentItem $contentItem */

            $transformer->setContentItem($contentItem);

            $post = $transformer->transform();

            $isInserted = $postRepository->insertOrUpdate($post);

            if ($isInserted) {
                $logger->info("Inserted new " . $post->post_type . " with slug '" . $post->post_name . "'");
                $insertedContent[] = $post;
            } else {
                $logger->info("Updated existing " . $post->post_type . " with slug '" . $post->post_name . "'");
                $updatedContent[] = $post;
            }
        }

        // return timezone
        date_default_timezone_set($oldTimezone);

        // format log contents
        /** @var TestHandler $logHandler */
        $logHandler = $container['logger.handler'];

        $output = array(
            'insertedContent' => $insertedContent,
            'updatedContent'  => $updatedContent,
            'logs'            => $logHandler->getRecords(),
        );

        return $this->render('publish/index.twig', $output);
    }

}
