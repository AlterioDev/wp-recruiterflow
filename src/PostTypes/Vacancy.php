<?php

/**
 * Vacancy Post Type Registration
 *
 * @package Alterio\WPRecruiterflow\PostTypes
 */

namespace Alterio\WPRecruiterflow\PostTypes;

use Alterio\WPRecruiterflow\Admin\Settings;

/**
 * Class Vacancy
 *
 * Handles registration and configuration of the Vacancy custom post type.
 *
 * @since 1.0.0
 */
class Vacancy
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    /**
     * Register the Vacancy custom post type.
     *
     * @since 1.0.0
     * @return void
     */
    public function register(): void
    {
        $singularSlug = Settings::getSingleVacancySlug() ?: 'vacancy';
        $pluralSlug = Settings::getVacancyArchiveSlug() ?: 'vacancies';

        register_post_type('vacancy', [
            'labels' => [
                'name' => __('Vacancies', 'wp-recruiterflow'),
                'singular_name' => __('Vacancy', 'wp-recruiterflow'),
                'add_new' => __('Add New', 'wp-recruiterflow'),
                'add_new_item' => __('Add New Vacancy', 'wp-recruiterflow'),
                'edit_item' => __('Edit Vacancy', 'wp-recruiterflow'),
                'new_item' => __('New Vacancy', 'wp-recruiterflow'),
                'view_item' => __('View Vacancy', 'wp-recruiterflow'),
                'search_items' => __('Search Vacancies', 'wp-recruiterflow'),
                'not_found' => __('No vacancies found', 'wp-recruiterflow'),
                'not_found_in_trash' => __('No vacancies found in Trash', 'wp-recruiterflow')
            ],
            'public' => true,
            'rewrite' => ['slug' => $singularSlug],
            'has_archive' => $pluralSlug,
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_icon' => 'dashicons-businessman',
        ]);
    }
}
