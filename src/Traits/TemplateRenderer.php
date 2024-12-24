<?php

namespace Alterio\WPRecruiterflow\Traits;

/**
 * Trait TemplateRenderer
 *
 * Provides template rendering functionality for the plugin.
 *
 * @package Alterio\WPRecruiterflow\Traits
 */
trait TemplateRenderer
{
    /**
     * Render a template file with optional data.
     *
     * @param string $template Template path relative to templates directory (without .php)
     * @param array  $data     Data to be extracted and available in template
     * @param string $type     Template type (admin, public, etc.)
     * @return void
     * @throws \RuntimeException If template file doesn't exist
     */
    protected function renderTemplate(string $template, array $data = [], string $type = 'admin'): void
    {
        $templatePath = $this->locateTemplate($template, $type);

        if (!$templatePath) {
            throw new \RuntimeException(
                sprintf('Template %s not found in %s templates directory', $template, $type)
            );
        }

        extract($data, EXTR_SKIP);
        include $templatePath;
    }

    /**
     * Locate a template file.
     *
     * Checks for template in theme directory first, then plugin directory.
     * Allows for template overrides in theme.
     *
     * @param string $template Template path relative to templates directory
     * @param string $type     Template type (admin, public, etc.)
     * @return string|false Full path to template file or false if not found
     */
    protected function locateTemplate(string $template, string $type): string|false
    {
        $template = ltrim($template, '/') . '.php';
        $templateFile = "{$type}/{$template}";

        // Check theme directory first
        $themeTemplate = locate_template('wp-recruiterflow/' . $templateFile);
        if ($themeTemplate) {
            return $themeTemplate;
        }

        // Fall back to plugin directory
        $pluginTemplate = WP_RECRUITERFLOW_PLUGIN_DIR . 'templates/' . $templateFile;
        if (file_exists($pluginTemplate)) {
            return $pluginTemplate;
        }

        return false;
    }
}
