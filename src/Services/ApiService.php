<?php

namespace Alterio\WPRecruiterflow\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Alterio\WPRecruiterflow\Admin\Settings;
use Alterio\WPRecruiterflow\Exceptions\ApiException;
use Alterio\WPRecruiterflow\Services\Logger;

class ApiService
{
    private const BASE_URL = 'https://api.recruiterflow.com';
    private Client $client;
    private string $apiKey;
    private Logger $logger;

    public function __construct()
    {
        $this->logger = Logger::getInstance();
        $this->apiKey = Settings::getApiKey();

        if (empty($this->apiKey)) {
            throw new ApiException('API key not configured');
        }

        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'RF-Api-Key' => $this->apiKey
            ],
            'http_errors' => true
        ]);
    }

    public function getVacancyList(): array
    {
        try {
            $response = $this->client->get('/api/external/job/list');
            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data)) {
                throw new ApiException('Failed to parse API response');
            }

            return array_filter($data, function ($job) {
                return isset($job['id'], $job['title'], $job['company']['name']);
            });
        } catch (GuzzleException $e) {
            error_log('Recruiterflow API Error: ' . $e->getMessage());
            throw new ApiException('Failed to fetch vacancies: ' . $e->getMessage());
        }
    }

    public function getDetailedVacancy(int $jobId): array
    {
        try {
            $response = $this->client->get('/api/external/job', [
                'query' => ['job_id' => $jobId]
            ]);

            $rawData = $response->getBody()->getContents();
            $this->logger->log("Detailed vacancy response", [
                'id' => $jobId,
                'data' => $rawData
            ]);

            $data = json_decode($rawData, true);

            // Based on the sample response, the data is directly in the root
            if (!isset($data['about_position'])) {
                throw new ApiException('Invalid job response: missing about_position');
            }

            return $data;
        } catch (GuzzleException $e) {
            $this->logger->log("API error", [
                'id' => $jobId,
                'error' => $e->getMessage()
            ]);
            throw new ApiException("Failed to fetch vacancy {$jobId}: " . $e->getMessage());
        }
    }

    private function formatDescription(array $job): string
    {
        $description = '';

        // Get description from custom fields if available
        foreach ($job['custom_fields'] ?? [] as $field) {
            if ($field['name'] === 'Description') {
                $description = $field['value'];
                break;
            }
        }

        // Add benefits if available
        foreach ($job['custom_fields'] ?? [] as $field) {
            if ($field['name'] === 'Benefits') {
                $description .= "\n\n" . $field['value'];
                break;
            }
        }

        return wp_kses_post($description);
    }

    private function formatSalary(array $job): string
    {
        if (empty($job['salary_range_start']) && empty($job['salary_range_end'])) {
            return '';
        }

        $currency = $job['salary_range_currency'] ?? 'EUR';
        $frequency = $job['salary_frequency'] ?? 'month';

        $start = $job['salary_range_start'] ? number_format($job['salary_range_start'], 0, ',', '.') : '?';
        $end = $job['salary_range_end'] ? number_format($job['salary_range_end'], 0, ',', '.') : '?';

        return "€{$start} - €{$end} per {$frequency}";
    }

    private function formatCustomFields(array $fields): array
    {
        $formatted = [];
        foreach ($fields as $field) {
            $formatted[$field['name']] = $field['value'];
        }
        return $formatted;
    }
}
