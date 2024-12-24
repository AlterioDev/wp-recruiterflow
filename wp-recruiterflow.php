<?php

/**
 * WP Recruiterflow
 *
 * @package     Alterio\WPRecruiterflow
 * @author      Alterio
 * @copyright   2024 Alterio
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: WP Recruiterflow
 * Plugin URI: https://alterio.nl
 * Description: Synchronizes vacancies from Recruiterflow to WordPress
 * Version: 1.0.0
 * Author: Alterio
 * Author URI: https://alterio.nl
 * Text Domain: wp-recruiterflow
 * Domain Path: /languages
 */

namespace Alterio\WPRecruiterflow;

if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * Main plugin class using singleton pattern.
 *
 * @since 1.0.0
 */
class Plugin
{
    /**
     * Plugin instance.
     *
     * @var Plugin|null
     */
    private static $instance = null;

    /**
     * Returns plugin instance.
     *
     * @since 1.0.0
     * @return Plugin
     */
    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        $this->defineConstants();
        $this->initHooks();
        $this->init();
    }

    /**
     * Define plugin constants.
     *
     * @since 1.0.0
     * @return void
     */
    private function defineConstants(): void
    {
        define('WP_RECRUITERFLOW_VERSION', '1.0.0');
        define('WP_RECRUITERFLOW_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('WP_RECRUITERFLOW_PLUGIN_URL', plugin_dir_url(__FILE__));
    }

    /**
     * Initialize WordPress hooks.
     *
     * @since 1.0.0
     * @return void
     */
    private function initHooks(): void
    {
        add_action('init', [$this, 'loadTextDomain']);
        add_action('plugins_loaded', [$this, 'init']);
    }

    /**
     * Load plugin text domain.
     *
     * @since 1.0.0
     * @return void
     */
    public function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'wp-recruiterflow',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    /**
     * Initialize plugin components.
     *
     * @since 1.0.0
     * @return void
     */
    public function init(): void
    {
        new Admin\Settings();
        new Admin\VacancyMetabox();
        new PostTypes\Vacancy();
        new Templates\Loader();
        new Handlers\SyncHandler();
    }
}

// Initialize the plugin
add_action('plugins_loaded', function () {
    Plugin::getInstance();
});
