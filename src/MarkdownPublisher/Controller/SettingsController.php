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
    const SLUG_PAGE_SETTINGS = 'mdpublisher_settings';

    const SETTING_GIT_REPO_PATH = 'git_repo_path';
    const SETTING_GIT_REPO_PATH_LABEL = 'Git Repository Path';

    const SETTING_GIT_REPO_BRANCH = 'git_repo_branch';
    const SETTING_GIT_REPO_BRANCH_LABEL = 'Git Repoository Branch';

    private $settings = array(
        array(
            'name' => self::SETTING_GIT_REPO_PATH,
            'label' => self::SETTING_GIT_REPO_PATH_LABEL,
            'template' => 'settings/fields/form-text.twig',
        ),
        array(
            'name' => self::SETTING_GIT_REPO_BRANCH,
            'label' => self::SETTING_GIT_REPO_BRANCH_LABEL,
            'template' => 'settings/fields/form-text.twig',
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
            $proxy->registerSetting(self::SLUG_PAGE_SETTINGS, $slug . $field['name']);
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
            'Markdown Publisher',
            'Markdown Publisher',
            'manage_options',
            self::SLUG_PAGE_SETTINGS,
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

        $settingsMarkup = $proxy->settingsFields(self::SLUG_PAGE_SETTINGS);

        $fieldMarkup = $this->buildFieldMarkup();
        $output = array(
            'settingsFormHiddenMarkup' => $settingsMarkup,
            'settingsFormFieldMarkup' => $fieldMarkup,
            'publishUrl' => $proxy->adminLink('mdpublisher_publish'),
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
        $slug = $container->getSlug();
        $fieldMarkup = '';
        foreach ($this->settings as $field) {
            $fieldMarkup .= $viewRenderer->render(
                $field['template'],
                array(
                    'name'  => $slug . $field['name'],
                    'label' => $field['label'],
                    'value' => $proxy->getOption($slug . $field['name']),
                )
            );
        }

        return $fieldMarkup;
    }
}
