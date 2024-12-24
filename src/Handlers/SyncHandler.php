<?php

namespace Alterio\WPRecruiterflow\Handlers;

use Alterio\WPRecruiterflow\Services\SyncService;

class SyncHandler
{
    public function __construct()
    {
        add_action('wp_ajax_wp_recruiterflow_sync', [$this, 'handleSync']);
    }

    public function handleSync(): void
    {
        check_ajax_referer('wp_recruiterflow_sync', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Insufficient permissions', 'wp-recruiterflow')]);
        }

        try {
            $syncService = new SyncService();
            $stats = $syncService->syncAllVacancies();

            $message = sprintf(
                __('Sync completed. Created: %d, Updated: %d, Failed: %d', 'wp-recruiterflow'),
                $stats['created'],
                $stats['updated'],
                $stats['failed']
            );

            wp_send_json_success(['message' => $message]);
        } catch (\Exception $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
}
