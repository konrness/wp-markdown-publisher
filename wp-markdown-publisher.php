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

use Nerdery\Plugin\Factory\Factory;
use Nerdery\Plugin\Router\Route;

/**
 * Bootstrap the plugin
 *
 * @return void
 */
function bootstrap()
{
    $factory = new Factory(array(
        'templatePath' => dirname(__FILE__) . '/resources/views',
        'prefix' => 'mdpublisher_',
        'slug' => 'mdpublisher_'
    ));
    $plugin = $factory->make();
    $plugin = $factory->registerDataServices($plugin);

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

    /*
     * Register routes
     */
    $plugin->registerRoute(
        new Route(
            '^mdpublisher/?',
            'controller.settings:indexAction'
        )
    );

    $plugin->run();
}

bootstrap();
