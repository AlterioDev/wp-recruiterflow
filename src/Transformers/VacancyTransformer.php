<?php

namespace Alterio\WPRecruiterflow\Transformers;

class VacancyTransformer
{
    public function transform(array $vacancy): array
    {
        return [
            'title' => $vacancy['title'],
            'content' => $this->formatDescription($vacancy),
            'external_id' => $vacancy['id'],
            'company' => $vacancy['company']['name'] ?? '',
            'location' => implode(', ', array_column($vacancy['locations'] ?? [], 'city')),
            'department' => $vacancy['department'] ?? '',
            'employment_type' => $vacancy['employment_type'] ?? '',
            'salary' => $this->formatSalary($vacancy),
            'apply_link' => $vacancy['apply_link'] ?? '',
            'created_at' => $vacancy['created_at'] ?? '',
            'requirements' => $this->extractCustomField($vacancy, 'Description'),
            'benefits' => $this->extractCustomField($vacancy, 'Benefits')
        ];
    }

    private function formatDescription(array $vacancy): string
    {
        $content = $vacancy['about_position'] ?? '';

        if (empty($content)) {
            $content = $this->extractCustomField($vacancy, 'Description');
        }

        $content = $this->cleanHtml($content);

        return $content;
    }

    private function cleanHtml(string $html): string
    {
        $html = str_replace('&nbsp;', ' ', $html);

        do {
            $previous = $html;
            $html = preg_replace([
                '/<([a-z][a-z0-9]*)[^>]*>\s*<\/\1>/',
                '/<strong[^>]*>.*?<\/strong>/',
                '/<br[^>]*>/'
            ], '', $html);
        } while ($previous !== $html);

        // Convert divs to paragraphs before other cleaning
        $html = preg_replace('/<div[^>]*>(.*?)<\/div>/', '<p>$1</p>', $html);

        $html = preg_replace([
            '/\s*style="[^"]*"/',
            '/\s*dir="[^"]*"/',
            '/\s*role="[^"]*"/',
            '/<span[^>]*>|<\/span>/',
            '/\s+/'
        ], [
            '',
            '',
            '',
            '',
            ' '
        ], $html);

        $html = wpautop($html);
        return wp_kses_post(trim($html));
    }

    private function formatSalary(array $vacancy): string
    {
        if (empty($vacancy['salary_range_start']) && empty($vacancy['salary_range_end'])) {
            return '';
        }

        $start = $vacancy['salary_range_start'] ? number_format($vacancy['salary_range_start'], 0, ',', '.') : '?';
        $end = $vacancy['salary_range_end'] ? number_format($vacancy['salary_range_end'], 0, ',', '.') : '?';

        return sprintf(
            '€%s - €%s per %s',
            $start,
            $end,
            $vacancy['salary_frequency'] ?? 'month'
        );
    }

    private function extractCustomField(array $vacancy, string $fieldName): string
    {
        foreach ($vacancy['custom_fields'] ?? [] as $field) {
            if ($field['name'] === $fieldName) {
                return wp_kses_post($field['value']);
            }
        }
        return '';
    }
}
