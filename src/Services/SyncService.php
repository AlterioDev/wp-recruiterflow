<?php

namespace Alterio\WPRecruiterflow\Services;

use Alterio\WPRecruiterflow\Transformers\VacancyTransformer;
use Alterio\WPRecruiterflow\Exceptions\ApiException;
use Alterio\WPRecruiterflow\Services\Logger;

class SyncService
{
    private ApiService $api;
    private VacancyTransformer $transformer;
    private Logger $logger;

    public function __construct()
    {
        $this->api = new ApiService();
        $this->transformer = new VacancyTransformer();
        $this->logger = Logger::getInstance();
    }

    public function syncAllVacancies(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'failed' => 0, 'skipped' => 0];

        try {
            $vacancies = $this->api->getVacancyList();
            $this->logger->log('Found vacancies', ['count' => count($vacancies)]);

            foreach ($vacancies as $vacancy) {
                try {
                    if (!$vacancy['is_open']) {
                        $this->logger->log('Skipping closed vacancy', [
                            'id' => $vacancy['id']
                        ]);
                        $stats['skipped']++;
                        continue;
                    }

                    $detailedVacancy = $this->api->getDetailedVacancy($vacancy['id']);
                    $this->logger->log('Got detailed vacancy', [
                        'id' => $vacancy['id'],
                        'response' => $detailedVacancy
                    ]);

                    $transformedData = $this->transformer->transform($detailedVacancy);
                    $this->logger->log('Transformed vacancy', [
                        'id' => $vacancy['id'],
                        'data' => $transformedData
                    ]);

                    $existingPost = $this->findExistingVacancy($vacancy['id']);

                    if ($existingPost) {
                        $this->updateVacancy($existingPost->ID, $transformedData);
                        $this->logger->log('Updated vacancy', ['id' => $vacancy['id']]);
                        $stats['updated']++;
                    } else {
                        $postId = $this->createVacancy($transformedData);
                        $this->logger->log('Created vacancy', [
                            'id' => $vacancy['id'],
                            'wp_id' => $postId
                        ]);
                        $stats['created']++;
                    }
                } catch (\Exception $e) {
                    $this->logger->log('Failed to sync vacancy', [
                        'id' => $vacancy['id'],
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $stats['failed']++;
                }
            }
        } catch (ApiException $e) {
            $this->logger->log('Sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return $stats;
    }

    private function findExistingVacancy(int $jobId): ?\WP_Post
    {
        $query = new \WP_Query([
            'post_type' => 'vacancy',
            'meta_key' => '_recruiterflow_job_id',
            'meta_value' => $jobId,
            'posts_per_page' => 1
        ]);

        return $query->have_posts() ? $query->posts[0] : null;
    }

    private function createVacancy(array $data): int
    {
        $slug = sprintf(
            '%s-%s-%s-%s',
            sanitize_title($data['employment_type'] ?? ''),
            sanitize_title($data['title']),
            sanitize_title($data['company']) ?? '',
            sanitize_title($data['location']) ?? '',
        );

        $postId = wp_insert_post([
            'post_type' => 'vacancy',
            'post_status' => 'publish',
            'post_title' => $data['title'],
            'post_name' => $slug,
            'post_content' => $data['content'],
            'meta_input' => [
                '_recruiterflow_job_id' => $data['external_id'],
                '_recruiterflow_company' => $data['company'],
                '_recruiterflow_location' => $data['location'],
                '_recruiterflow_department' => $data['department'],
                '_recruiterflow_employment_type' => $data['employment_type'],
                '_recruiterflow_salary' => $data['salary'],
                '_recruiterflow_apply_link' => $data['apply_link'],
                '_recruiterflow_created_at' => $data['created_at'],
                '_recruiterflow_requirements' => $data['requirements'],
                '_recruiterflow_benefits' => $data['benefits']
            ]
        ]);

        if (is_wp_error($postId)) {
            throw new \Exception($postId->get_error_message());
        }

        return $postId;
    }

    private function updateVacancy(int $postId, array $data): void
    {
        $updated = wp_update_post([
            'ID' => $postId,
            'post_title' => $data['title'],
            'post_content' => $data['content'],
            'meta_input' => [
                '_recruiterflow_job_id' => $data['external_id'],
                '_recruiterflow_company' => $data['company'],
                '_recruiterflow_location' => $data['location'],
                '_recruiterflow_department' => $data['department'],
                '_recruiterflow_employment_type' => $data['employment_type'],
                '_recruiterflow_salary' => $data['salary'],
                '_recruiterflow_apply_link' => $data['apply_link'],
                '_recruiterflow_created_at' => $data['created_at'],
                '_recruiterflow_requirements' => $data['requirements'],
                '_recruiterflow_benefits' => $data['benefits']
            ]
        ]);

        if (is_wp_error($updated)) {
            throw new \Exception($updated->get_error_message());
        }
    }
}
