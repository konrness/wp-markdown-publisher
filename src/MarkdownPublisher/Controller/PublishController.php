<?php

namespace MarkdownPublisher\Controller;

use Nerdery\Plugin\Controller\Controller;
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

        $updatedContent = array();
        $insertedContent = array();
        foreach ($this->getTestData() as $content)
        {
            // find an existing page
            $search = array(
                'name' => $content['post_name'],
                'post_type' => $content['post_type'],
                'posts_per_page' => 1,
            );
            $existingPost = \get_posts($search);

            if (! $existingPost) {
                \wp_insert_post($content);
                $insertedContent[] = $content;
            } else {
                $content['ID'] = $existingPost[0]->ID;
                \wp_update_post($content);
                $updatedContent[] = $content;
            }
        }


        $output = array(
            'insertedContent' => $insertedContent,
            'updatedContent' => $updatedContent,
        );

        return $this->render('publish/index.twig', $output);
    }

    public function getTestData()
    {
        return array(
            array (
            'post_content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur justo erat, volutpat at risus sit amet, venenatis sodales ante.</p>

<ul>
<li>Class</li>
<li>aptent</li>
<li>taciti</li>
<li>sociosqu</li>
<li>ad litora</li>
<li>torquent per conubia</li>
<li>nostra</li>
</ul>

<p>Donec tempus ultricies magna, et hendrerit nisl. Integer id auctor augue. Nullam ut vestibulum lectus. Suspendisse nisl est, ultrices a vulputate ac, dapibus eu tellus. Pellentesque sed libero condimentum, accumsan odio eget, fringilla libero. Integer rhoncus odio eget tortor dictum, sed elementum metus dapibus. Quisque adipiscing pulvinar porta. Proin hendrerit bibendum elit, sed ultrices tellus tincidunt consectetur. Morbi eget ipsum at dui feugiat pharetra in ut mauris.</p>
',
            'post_name' => 'lorem-ipsum-test-2',
            'post_title' => 'Lorem ipsum Test 2',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => NULL,
            'ping_status' => NULL,
            'post_parent' => 'example1',
            'menu_order' => NULL,
            'post_excerpt' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur justo erat, volutpat at risus sit amet, venenatis sodales ante.</p>
',
            'post_date' => NULL,
            'post_category' =>
                array (
                    0 => 'test',
                    1 => 'one',
                    2 => 'two',
                ),
            'tags_input' =>
                array (
                    0 => 'tag1',
                    1 => 'tag2',
                    2 => 'tag3',
                ),
            'page_template' => NULL,
        ),
        array (
        'post_content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur justo erat, volutpat at risus sit amet, venenatis sodales ante.</p>

<ul>
<li>Class</li>
<li>aptent</li>
<li>taciti</li>
<li>sociosqu</li>
<li>ad litora</li>
<li>torquent per conubia</li>
<li>nostra</li>
</ul>

<p>Donec tempus ultricies magna, et hendrerit nisl. Integer id auctor augue. Nullam ut vestibulum lectus. Suspendisse nisl est, ultrices a vulputate ac, dapibus eu tellus. Pellentesque sed libero condimentum, accumsan odio eget, fringilla libero. Integer rhoncus odio eget tortor dictum, sed elementum metus dapibus. Quisque adipiscing pulvinar porta. Proin hendrerit bibendum elit, sed ultrices tellus tincidunt consectetur. Morbi eget ipsum at dui feugiat pharetra in ut mauris.</p>
',
        'post_name' => 'lorem-ipsum-test-1',
        'post_title' => 'Lorem ipsum Test 1',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => NULL,
        'ping_status' => NULL,
        'post_parent' => NULL,
        'menu_order' => NULL,
        'post_excerpt' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur justo erat, volutpat at risus sit amet, venenatis sodales ante.</p>
',
        'post_date' => NULL,
        'post_category' =>
            array (
                0 => 'test',
                1 => 'one',
                2 => 'two',
            ),
        'tags_input' =>
            array (
                0 => 'tag1',
                1 => 'tag2',
                2 => 'tag3',
            ),
        'page_template' => NULL,
    ));
    }

}
