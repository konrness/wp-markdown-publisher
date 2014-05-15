<?php
/*
Plugin Name: Markdown Publisher
Plugin URI: https://github.com/konrness/wp-markdown-publisher
Description: A WordPress plugin for publishing pages and blog posts from markdown files served by a git repository
Version: 0.0.1
Author: Konr Ness
Author URI: http://konrness.com
*/

namespace MarkdownPublisher;

require_once 'vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use MarkdownPublisher\WordPress\Proxy;
use MarkdownPublisher\WordPress\Repository\Author;
use MarkdownPublisher\WordPress\Repository\Category;
use MarkdownPublisher\WordPress\Repository\Post;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Nerdery\Plugin\Factory\Factory;
use Nerdery\Plugin\Router\Route;

use Symfony\Component\Finder\Finder;
use Fabricius\Loader\FileLoader;
use Doctrine\Common\Cache\ArrayCache;

use Monolog\Handler\StreamHandler;
/**
 * Bootstrap the plugin
 *
 * @return void
 */
function bootstrap()
{
    ini_set('xdebug.var_display_max_depth', 20);

    $factory = new Factory(array(
        'templatePath' => dirname(__FILE__) . '/resources/views',
        'prefix' => 'mdpublisher_',
        'slug' => 'mdpublisher_'
    ));
    $plugin = $factory->make();
    $plugin = $factory->registerDataServices($plugin);

    // replace the default provided Proxy with enhanced Proxy
    $plugin['wp-proxy'] = new Proxy();

    /*
     * Register controllers
     *
     * All of these controllers are loaded immediately every time this plugin
     * is loaded. Keep this in mind when considering performance. However, as
     * the controllers are the "meat and potatoes" of the plugin, it only makes
     * sense that they be loaded immediately so that they can hook into the
     * necessary WordPress event calls they need to respond to.
     */
    $plugin['controller.settings'] = new Controller\SettingsController($plugin);
    $plugin['controller.publish'] = new Controller\PublishController($plugin);

    $plugin['logger.handler'] = function() {
        return new TestHandler();
    };

    $plugin['logger'] = function ($plugin) {

        $logger = new Logger("Markdown Publisher");

        $logger->pushHandler($plugin['logger.handler']);

        $logger->info("Starting logger");

        return $logger;
    };

    $plugin['library'] = function () {

        AnnotationRegistry::registerLoader('class_exists');

        $libraryBuilder = LibraryBuilder::create();

        $libraryBuilder->setCacheDir(__DIR__ . '/cache')
                       ->setExcerptDelimiter('<!-- more -->');

        $library = $libraryBuilder->build();

        $finder = new Finder();
        $loader = new FileLoader($finder, __DIR__ . '/_content/test');

        $cache = new ArrayCache();
        $library->registerRepository('MarkdownPublisher\Content\ContentItem', $loader, $cache);

        return $library;
    };

    $plugin['repository.post'] = function($plugin) {
        return new Post($plugin['wp-proxy']);
    };

    $plugin['repository.category'] = function($plugin) {
        return new Category($plugin['wp-proxy']);
    };

    $plugin['repository.author'] = function($plugin) {
        return new Author($plugin['wp-proxy']);
    };

    /*
     * Register routes
     */
//    $plugin->registerRoute(
//        new Route(
//            '^mdpublisher/?',
//            'controller.settings:indexAction'
//        )
//    );

    $plugin->run();
}

bootstrap();
