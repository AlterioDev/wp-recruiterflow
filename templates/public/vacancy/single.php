<?php

/**
 * Template for displaying single vacancy posts
 *
 * @package Alterio\WPRecruiterflow
 */

get_header();

$meta = get_post_meta(get_the_ID());
$location = $meta['_recruiterflow_location'][0] ?? '';
$salary = $meta['_recruiterflow_salary'][0] ?? '';
$type = $meta['_recruiterflow_type'][0] ?? '';
$external_url = $meta['_recruiterflow_external_url'][0] ?? '';
?>

<main class="vacancy-single">
    <div class="container">
        <article <?php post_class(); ?>>
            <header class="vacancy-header">
                <h1 class="vacancy-title"><?php the_title(); ?></h1>

                <?php if ($location || $type || $salary): ?>
                    <div class="vacancy-meta">
                        <?php if ($location): ?>
                            <span class="vacancy-location"><?php echo esc_html($location); ?></span>
                        <?php endif; ?>

                        <?php if ($type): ?>
                            <span class="vacancy-type"><?php echo esc_html($type); ?></span>
                        <?php endif; ?>

                        <?php if ($salary): ?>
                            <span class="vacancy-salary"><?php echo esc_html($salary); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="vacancy-content">
                <?php the_content(); ?>
            </div>

            <?php if ($external_url): ?>
                <div class="vacancy-action">
                    <a href="<?php echo esc_url($external_url); ?>" class="vacancy-apply-button" target="_blank" rel="noopener">
                        <?php esc_html_e('Apply Now', 'wp-recruiterflow'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </article>
    </div>
</main>

<?php get_footer(); ?>