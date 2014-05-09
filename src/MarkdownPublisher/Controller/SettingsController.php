<?php

namespace MarkdownPublisher\Controller;

use Nerdery\Plugin\Controller\Controller;
/**
 * Class SettingsController
 *
 * @author Konr Ness <konrness@gmail.com>
 */
class SettingsController extends Controller
{
    /*
     * Constants
     */
    const SETTINGS_PAGE_SLUG = 'mdpublisher_settings';

    const SETTING_PLUGIN_NAME = 'plugin_name';
    const SETTING_PLUGIN_NAME_LABEL = 'Plugin Name';

    const SETTING_PLUGIN_DESCRIPTION = 'plugin_description';
    const SETTING_PLUGIN_DESCRIPTION_LABEL = 'Description Area';

    const SETTING_PLUGIN_VERSION = 'plugin_version';
    const SETTING_PLUGIN_VERSION_LABEL = 'Plugin Version';

    private $settings = array(
        array(
            'name' => self::SETTING_PLUGIN_NAME,
            'label' => self::SETTING_PLUGIN_NAME_LABEL,
            'template' => 'settings/fields/plugin-name.twig',
        ),
        array(
            'name' => self::SETTING_PLUGIN_DESCRIPTION,
            'label' => self::SETTING_PLUGIN_DESCRIPTION_LABEL,
            'template' => 'settings/fields/plugin-description.twig',
        ),
        array(
            'name' => self::SETTING_PLUGIN_VERSION,
            'label' => self::SETTING_PLUGIN_VERSION_LABEL,
            'template' => 'settings/fields/plugin-version.twig',
        )
    );

    /**
     * Initialize controller
     *
     * @return self
     */
    public function initialize()
    {
        $proxy = $this->getProxy();
        $container = $this->getContainer();
        $pluginUrl = $container['plugin.url'];
        $proxy->addAction('admin_enqueue_scripts', function () use ($proxy, $pluginUrl) {
//            $proxy->wpEnqueueScript(
//                'jquery-datetime',
//                "{$pluginUrl}/resources/js/jquery.datetimepicker.js",
//                array('jquery')
//            );
//            $proxy->wpEnqueueStyle(
//                'jquery-datetime',
//                "{$pluginUrl}/resources/css/jquery.datetimepicker.css"
//            );
        });
    }

    /**
     * Initialize the admin interface
     *
     * @return $this
     */
    public function initializeAdmin()
    {
        $proxy = $this->getProxy();
        $slug = $this->getContainer()->getSlug();
        foreach ($this->settings as $field) {
            $proxy->registerSetting(self::SETTINGS_PAGE_SLUG, $slug . '_' . $field['name']);
        }
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function registerAdminRoutes()
    {
        // Register the settings page
        $controller = $this;
        $proxy = $this->getProxy();
        $proxy->addMenuPage(
            'Clog Culprits',
            'Clog Culprits',
            'manage_options',
            self::SETTINGS_PAGE_SLUG,
            function () use ($controller) {
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

        $settingsMarkup = $proxy->settingsFields(self::SETTINGS_PAGE_SLUG);
        $fieldMarkup = $this->buildFieldMarkup();
        $output = array(
            'settingsMarkup' => $settingsMarkup,
            'fieldMarkup' => $fieldMarkup,
        );

        return $this->render('settings/index.twig', $output);
    }

    /**
     * Build the settings field markup
     *
     * @return string
     */
    public function buildFieldMarkup()
    {
        $proxy = $this->getProxy();
        $container = $this->getContainer();
        $viewRenderer = $container->getViewRenderer();
        $fieldMarkup = '';
        foreach ($this->settings as $field) {
            $fieldMarkup .= $viewRenderer->render(
                $field['template'],
                array(
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'value' => $proxy->getOption($field['name']),
                )
            );
        }

        return $fieldMarkup;
    }
}
