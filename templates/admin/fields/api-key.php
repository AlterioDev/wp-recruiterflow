<div class="api-key-wrapper">
    <input type="password"
        id="api_key_field"
        name="<?php echo esc_attr($name); ?>[api_key]"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text">
    <button type="button" id="toggle_api_key" class="button">
        <?php esc_html_e('Show', 'wp-recruiterflow'); ?>
    </button>
</div>
<div>
    <small class="description">
        <?php echo esc_html__('Enter your Recruiterflow API key. Make sure to keep this private, since this will give access to pesonal information.', 'wp-recruiterflow'); ?>
    </small>
</div>

<script>
    document.getElementById('toggle_api_key').addEventListener('click', function() {
        const input = document.getElementById('api_key_field');
        const button = this;

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = 'Hide';
        } else {
            input.type = 'password';
            button.textContent = 'Show';
        }
    });
</script>