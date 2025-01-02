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

<main class="vacancy">
    <div class="vacancy__container">
        <article <?php post_class('vacancy__article'); ?>>
            <header class="vacancy__header">
                <h1 class="vacancy__title"><?php the_title(); ?></h1>

                <?php if ($location || $type || $salary): ?>
                    <div class="vacancy__meta">
                        <?php if ($location): ?>
                            <span class="vacancy__meta-item vacancy__meta-item--location"><?php echo esc_html($location); ?></span>
                        <?php endif; ?>

                        <?php if ($type): ?>
                            <span class="vacancy__meta-item vacancy__meta-item--type"><?php echo esc_html($type); ?></span>
                        <?php endif; ?>

                        <?php if ($salary): ?>
                            <span class="vacancy__meta-item vacancy__meta-item--salary"><?php echo esc_html($salary); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="vacancy__content">
                <?php the_content(); ?>
            </div>

            <?php if ($external_url): ?>
                <div class="vacancy__action">
                    <a href="<?php echo esc_url($external_url); ?>" class="vacancy__button vacancy__button--apply" target="_blank" rel="noopener">
                        <?php esc_html_e('Apply Now', 'wp-recruiterflow'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </article>
    </div>
</main>

<?php get_footer(); ?>