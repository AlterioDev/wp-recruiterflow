<?php

namespace Alterio\WPRecruiterflow\Admin;

class VacancyMetabox
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addMetaBox']);
    }

    public function addMetaBox(): void
    {
        add_meta_box(
            'recruiterflow_vacancy_details',
            __('Vacancy Details', 'wp-recruiterflow'),
            [$this, 'renderMetaBox'],
            'vacancy',
            'normal',
            'high'
        );
    }

    public function renderMetaBox(\WP_Post $post): void
    {
        $meta = get_post_meta($post->ID);
?>
        <div class="vacancy-meta-box">
            <style>
                .vacancy-meta-box table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .vacancy-meta-box th {
                    text-align: left;
                    padding: 10px;
                    width: 200px;
                }

                .vacancy-meta-box td {
                    padding: 10px;
                }

                .vacancy-meta-box tr:nth-child(odd) {
                    background: #f9f9f9;
                }
            </style>
            <table>
                <tr>
                    <th><?php esc_html_e('Job ID', 'wp-recruiterflow'); ?></th>
                    <td><?php echo esc_html($meta['_recruiterflow_job_id'][0] ?? ''); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Company', 'wp-recruiterflow'); ?></th>
                    <td><?php echo esc_html($meta['_recruiterflow_company'][0] ?? ''); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Location', 'wp-recruiterflow'); ?></th>
                    <td><?php echo esc_html($meta['_recruiterflow_location'][0] ?? ''); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Department', 'wp-recruiterflow'); ?></th>
                    <td><?php echo esc_html($meta['_recruiterflow_department'][0] ?? ''); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Employment Type', 'wp-recruiterflow'); ?></th>
                    <td><?php echo esc_html($meta['_recruiterflow_employment_type'][0] ?? ''); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Salary', 'wp-recruiterflow'); ?></th>
                    <td><?php echo esc_html($meta['_recruiterflow_salary'][0] ?? ''); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Apply Link', 'wp-recruiterflow'); ?></th>
                    <td>
                        <?php if (!empty($meta['_recruiterflow_apply_link'][0])): ?>
                            <a href="<?php echo esc_url($meta['_recruiterflow_apply_link'][0]); ?>" target="_blank">
                                <?php esc_html_e('Apply Now', 'wp-recruiterflow'); ?>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Last Updated', 'wp-recruiterflow'); ?></th>
                    <td><?php echo esc_html(get_date_from_gmt($meta['_recruiterflow_updated_at'][0] ?? '')); ?></td>
                </tr>
            </table>
        </div>
<?php
    }
}
