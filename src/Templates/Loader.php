<?php

/**
 * Template Loader
 * 
 * @package Alterio\WPRecruiterflow\Templates
 */

namespace Alterio\WPRecruiterflow\Templates;

/**
 * Class Loader
 * Handles loading of default templates that can be overridden by theme
 */
class Loader
{
    public function __construct()
    {
        add_filter('single_template', [$this, 'loadVacancyTemplate']);
    }

    /**
     * Load vacancy template with theme override support
     */
    public function loadVacancyTemplate(string $template): string
    {
        if (is_singular('vacancy')) {
            $theme_template = locate_template('single-vacancy.php');

            if ($theme_template) {
                return $theme_template;
            }

            $plugin_template = WP_RECRUITERFLOW_PLUGIN_DIR . 'templates/public/vacancy/single.php';

            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        return $template;
    }
}
