<div>
    <input type="text"
        name="<?php echo esc_attr($name); ?>[archive_slug]"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text">
</div>
<small class="description">
    <?php echo esc_html__('Enter the slug for vacancy archives. Default is \'vacancies\'.', 'wp-recruiterflow'); ?>
</small>