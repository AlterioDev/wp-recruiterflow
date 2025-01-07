<?php

namespace Alterio\WPRecruiterflow\Templates;

class Loader
{
    public function __construct()
    {
        add_filter('single_template', [$this, 'loadVacancyTemplate']);
        add_filter('archive_template', [$this, 'loadVacancyArchiveTemplate']);
    }

    public function loadVacancyTemplate(string $template): string
    {
        if (is_singular('vacancy')) {
            $theme_template = locate_template([
                'wp-recruiterflow/public/vacancy/single.php',
                'single-vacancy.php'
            ]);

            if ($theme_template) {
                return $theme_template;
            }

            $plugin_template = WP_RECRUITERFLOW_PLUGIN_DIR . 'templates/public/vacancy/single-vacancy.php';

            return file_exists($plugin_template) ? $plugin_template : $template;
        }
        return $template;
    }

    public function loadVacancyArchiveTemplate(string $template): string
    {
        if (is_post_type_archive('vacancy')) {
            $theme_template = locate_template([
                'wp-recruiterflow/public/vacancy/archive.php',
                'archive-vacancy.php'
            ]);

            if ($theme_template) {
                return $theme_template;
            }

            $plugin_template = WP_RECRUITERFLOW_PLUGIN_DIR . 'templates/public/vacancy/archive-vacancy.php';

            return file_exists($plugin_template) ? $plugin_template : $template;
        }
        return $template;
    }
}
