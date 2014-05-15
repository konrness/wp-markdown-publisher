<?php

namespace MarkdownPublisher\Controller;

use MarkdownPublisher\WordPress\Repository\Author;
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

        $contentItems = $library->getRepository('MarkdownPublisher\Content\ContentItem')->query();

        $updatedContent = array();
        $insertedContent = array();
        foreach ($contentItems as $contentItem)
        {
            /** @var ContentItem $contentItem */

            // transform parsed data to WordPress posts
            $transformer = new ContentItemTransformer();

            $transformer->setLogger($logger);
            $transformer->setAuthorRepository(new Author($proxy));
            $transformer->setContentItem($contentItem);

            $post = $transformer->transform();

            /*
            // find an existing page
            $search = array(
                'name' => $post->post_name,
                'post_type' => $post->post_type,
                'posts_per_page' => 1,
            );
            $existingPost = \get_posts($search);

            if (! $existingPost) {
                \wp_insert_post($contentItem);
                $insertedContent[] = $contentItem;
            } else {
                $contentItem['ID'] = $existingPost[0]->ID;
                \wp_update_post($contentItem);
                $updatedContent[] = $contentItem;
            }
            */
        }

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
