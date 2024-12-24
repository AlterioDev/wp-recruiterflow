<?php

namespace Alterio\WPRecruiterflow\Services;

class Logger
{
    private static string $logFile;
    private static ?self $instance = null;

    private function __construct()
    {
        self::$logFile = WP_RECRUITERFLOW_PLUGIN_DIR . 'debug.log';
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log(string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? ' ' . json_encode($context) : '';
        $logMessage = "[{$timestamp}] {$message}{$contextString}\n";

        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }
}
