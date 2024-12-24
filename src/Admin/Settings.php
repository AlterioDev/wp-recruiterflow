<?php

namespace Alterio\WPRecruiterflow\Admin;

use Alterio\WPRecruiterflow\Traits\TemplateRenderer;

/**
 * Class Settings
 *
 * @package Alterio\WPRecruiterflow\Admin
 */
class Settings
{
    use TemplateRenderer;

    private const OPTION_GROUP = 'wp_recruiterflow_settings';
    private const OPTION_NAME = 'wp_recruiterflow_options';
    private const MENU_SLUG = 'wp-recruiterflow-settings';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('update_option_' . self::OPTION_NAME, [$this, 'flushRules'], 10, 2);
        add_action('init', [$this, 'maybeFlushRules'], 999);
    }

    /**
     * Flush rewrite rules if vacancy slug has changed
     *
     * @param array $oldValue Previous option value
     * @param array $newValue New option value
     */
    public function flushRules(): void
    {
        update_option('wp_recruiterflow_needs_flush', true);
    }

    public function maybeFlushRules(): void
    {
        if (get_option('wp_recruiterflow_needs_flush')) {
            delete_option('wp_recruiterflow_needs_flush');
            flush_rewrite_rules();
        }
    }

    public function addSettingsPage(): void
    {
        add_options_page(
            __('WP Recruiterflow Settings', 'wp-recruiterflow'),
            __('WP Recruiterflow', 'wp-recruiterflow'),
            'manage_options',
            self::MENU_SLUG,
            [$this, 'renderSettingsPage']
        );
    }

    public function registerSettings(): void
    {
        register_setting(
            self::OPTION_GROUP,
            self::OPTION_NAME,
            [
                'sanitize_callback' => [$this, 'sanitizeOptions']
            ]
        );

        // General section
        add_settings_section(
            'general_section',
            __('General Settings', 'wp-recruiterflow'),
            null,
            self::MENU_SLUG
        );

        add_settings_field(
            'api_key',
            __('API Key', 'wp-recruiterflow'),
            [$this, 'renderApiKeyField'],
            self::MENU_SLUG,
            'general_section',
            array('label_for' => 'api_key')
        );

        add_settings_field(
            'vacancy_slug',
            __('Vacancy slug (singular)', 'wp-recruiterflow'),
            [$this, 'renderSingularSlugField'],
            self::MENU_SLUG,
            'general_section',
            array('label_for' => 'vacancy_slug')
        );

        add_settings_field(
            'archive_slug',
            __('Vacancy slug (plural)', 'wp-recruiterflow'),
            [$this, 'renderPluralSlugField'],
            self::MENU_SLUG,
            'general_section',
            array('label_for' => 'archive_slug')
        );

        // Sync section
        add_settings_section(
            'sync_section',
            __('Vacancy Synchronization', 'wp-recruiterflow'),
            [$this, 'renderSyncSectionInfo'],
            self::MENU_SLUG
        );

        add_settings_field(
            'sync_button',
            __('Click here to manually sync vacancies from Recruiterflow.', 'wp-recruiterflow'),
            [$this, 'renderSyncButton'],
            self::MENU_SLUG,
            'sync_section'
        );
    }

    public function renderApiKeyField(): void
    {
        $options = get_option(self::OPTION_NAME);
        $this->renderTemplate('fields/api-key', [
            'name' => self::OPTION_NAME,
            'value' => $options['api_key'] ?? ''
        ]);
    }

    public static function getApiKey(): string
    {
        $options = get_option(self::OPTION_NAME);
        return $options['api_key'] ?? '';
    }

    public function renderSettingsPage(): void
    {
        $this->renderTemplate('settings-page', [
            'title' => get_admin_page_title(),
            'option_group' => self::OPTION_GROUP,
            'menu_slug' => self::MENU_SLUG
        ]);
    }

    public function renderSingularSlugField(): void
    {
        $options = get_option(self::OPTION_NAME);

        $this->renderTemplate('fields/singular-slug', [
            'name' => self::OPTION_NAME,
            'value' => $options['vacancy_slug'] ?? 'vacancy'
        ]);
    }

    public function renderPluralSlugField(): void
    {
        $options = get_option(self::OPTION_NAME);

        $this->renderTemplate('fields/plural-slug', [
            'name' => self::OPTION_NAME,
            'value' => $options['archive_slug'] ?? 'vacancies'
        ]);
    }

    public function renderSyncSectionInfo(): void
    {
        $this->renderTemplate('sections/sync-info');
    }

    public function renderSyncButton(): void
    {
        $nonce = wp_create_nonce('wp_recruiterflow_sync');
        $this->renderTemplate('fields/sync-button', [
            'nonce' => $nonce
        ]);
    }

    public static function getSingleVacancySlug(): string
    {
        $options = get_option(self::OPTION_NAME);
        return $options['vacancy_slug'] ?? 'vacancy';
    }

    public static function getVacancyArchiveSlug(): string
    {
        $options = get_option(self::OPTION_NAME);
        return $options['archive_slug'] ?? 'vacancies';
    }

    private function renderTemplate(string $template, array $data = []): void
    {
        extract($data);
        include WP_RECRUITERFLOW_PLUGIN_DIR . 'templates/admin/' . $template . '.php';
    }

    public function sanitizeOptions($input): array
    {
        $sanitized = [];
        $sanitized['api_key'] = sanitize_text_field($input['api_key'] ?? '');
        $singular_slug = sanitize_title($input['vacancy_slug'] ?? '');
        $sanitized['vacancy_slug'] = empty($singular_slug) ? 'vacancy' : $singular_slug;
        $plural_slug = sanitize_title($input['archive_slug'] ?? '');
        $sanitized['archive_slug'] = empty($plural_slug) ? 'vacancy' : $plural_slug;

        return $sanitized;
    }
}
