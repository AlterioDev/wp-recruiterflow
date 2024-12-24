<?php
get_header();
?>

<main class="vacancy-archive">
    <div class="container">
        <header class="archive-header">
            <h1><?php esc_html_e('Vacancies', 'wp-recruiterflow'); ?></h1>
        </header>

        <?php if (have_posts()): ?>
            <div class="vacancy-grid">
                <?php while (have_posts()): the_post();
                    $meta = get_post_meta(get_the_ID());
                    $location = $meta['_recruiterflow_location'][0] ?? '';
                    $type = $meta['_recruiterflow_type'][0] ?? '';
                    $salary = $meta['_recruiterflow_salary'][0] ?? '';
                ?>
                    <article <?php post_class('vacancy-card'); ?>>
                        <h2 class="vacancy-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>

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

                        <div class="vacancy-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); ?>

        <?php else: ?>
            <p><?php esc_html_e('No vacancies found.', 'wp-recruiterflow'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>