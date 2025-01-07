<?php
get_header();
?>
<main class="vacancy-archive">
    <div class="container">
        <?php if (have_posts()): ?>
            <div class="vacancy-archive__grid">
                <?php while (have_posts()): the_post();
                    $meta = get_post_meta(get_the_ID());
                    $location = $meta['_recruiterflow_location'][0] ?? '';
                    $type = $meta['_recruiterflow_type'][0] ?? '';
                    $salary = $meta['_recruiterflow_salary'][0] ?? '';
                ?>
                    <a href="<?php the_permalink(); ?>">
                        <article <?php post_class('vacancy-archive__card'); ?>>
                            <h2 class="vacancy-archive__title">
                                <?php the_title(); ?>
                            </h2>
                            <?php if ($location || $type || $salary): ?>
                                <div class="vacancy-archive__meta">
                                    <?php if ($location): ?>
                                        <span class="vacancy-archive__location"><?php echo esc_html($location); ?></span>
                                    <?php endif; ?>

                                    <?php if ($type): ?>
                                        <span class="vacancy-archive__type"><?php echo esc_html($type); ?></span>
                                    <?php endif; ?>

                                    <?php if ($salary): ?>
                                        <span class="vacancy-archive__salary"><?php echo esc_html($salary); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="vacancy-archive__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <button class="vacancy-archive__button">
                                <?php esc_html_e('Open vacancy', 'wp-recruiterflow'); ?>
                            </button>
                        </article>
                    </a>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else: ?>
            <p><?php esc_html_e('No vacancies found.', 'wp-recruiterflow'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>