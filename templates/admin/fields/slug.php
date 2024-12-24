<div>
    <input type="text"
        name="<?php echo esc_attr($name); ?>[vacancy_slug]"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text">
</div>
<small class="description">
    <?php echo esc_html__('Enter the slug for vacancy archives (e.g., \'job\', \'position\'). This is the part in the URL before the vacancy slug. Default is \'vacancy\'.', 'wp-recruiterflow'); ?>
</small>