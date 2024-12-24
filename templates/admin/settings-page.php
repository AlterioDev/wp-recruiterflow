<div class="wrap wp-recruiterflow-settings">

    <div class="alterio-header">
        <a href="https://alterio.nl" target="_blank" rel="noopener noreferrer" class="alterio-brand">
            <svg width="28" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 107 72">
                <circle cx="71.475" cy="36.191" r="35.525" fill="#3A3A3A" />
                <path fill="#78F4A2" fill-rule="evenodd" d="M39.047.797h-6.89v28.197l-20.58-19.01-4.686 5.073 19.372 17.896H0v6.89h26.87L6.89 58.3l4.687 5.073 20.58-19.01V72h6.89V44.762l20.146 18.61L63.88 58.3 43.9 39.844h27.302v-6.891H44.507L63.88 15.057l-4.687-5.073-20.146 18.61V.798Z" clip-rule="evenodd" />
            </svg>
            <span class="alterio-text">Alterio</span>
        </a>
    </div>
    <h1><?php echo esc_html($title); ?></h1>

    <div class="wp-recruiterflow-intro">
        <div class="wp-recruiterflow-intro__content">
            <div class="wp-recruiterflow-intro__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="wp-recruiterflow-intro__text">
                <h2><?php esc_html_e('Welcome to WP Recruiterflow', 'wp-recruiterflow'); ?></h2>
                <p><?php echo esc_html__('This plugin synchronizes job vacancies from your Recruiterflow account to WordPress. Configure your API key below to start the sync process and customize how vacancies appear on your site.', 'wp-recruiterflow'); ?></p>
            </div>
        </div>
    </div>

    <div class="wp-recruiterflow-settings-container">
        <form action="options.php" method="post">
            <?php settings_fields($option_group); ?>

            <div class="settings-section">
                <h2><?php esc_html_e('General Settings', 'wp-recruiterflow'); ?></h2>
                <?php do_settings_fields($menu_slug, 'general_section'); ?>
                <?php submit_button(); ?>
            </div>
        </form>
    </div>

    <div class="wp-recruiterflow-settings-container mt-30">
        <div class="settings-section">
            <h2><?php esc_html_e('Vacancy Synchronization', 'wp-recruiterflow'); ?></h2>
            <p class="section-description">
                <?php esc_html_e('Synchronize your vacancies from Recruiterflow to WordPress. The sync process will create or update vacancy posts based on your Recruiterflow data.', 'wp-recruiterflow'); ?>
            </p>
            <?php do_settings_fields($menu_slug, 'sync_section'); ?>
        </div>
    </div>

</div>

<style>
    .wp-recruiterflow-settings label {
        display: block;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }

    .wp-recruiterflow-settings small {
        display: block;
        max-width: 24rem;
    }

    .wp-recruiterflow-settings p {
        max-width: 32rem;
    }

    .wp-recruiterflow-settings .submit {
        padding: 0;
    }

    .mt-30 {
        margin-top: 30px;
    }

    .settings-section {
        margin-bottom: 0;
    }

    .settings-section h2 {
        margin: 0 0 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f1;
        font-size: 1.2em;
    }

    .section-description {
        margin: -8px 0 20px;
        color: #50575e;
        font-size: 14px;
    }

    .alterio-header {
        margin: -10px -20px 20px;
        padding: 16px 24px;
        background: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, .05);
    }

    .alterio-brand {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        width: fit-content;
    }

    .alterio-text {
        font-size: 16px;
        font-weight: 500;
        color: #3A3A3A;
    }

    .alterio-brand:hover .alterio-text {
        color: #78F4A2;
    }

    .wp-recruiterflow-intro {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        margin: 20px 0 30px;
        padding: 24px;
    }

    .wp-recruiterflow-intro__content {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .wp-recruiterflow-intro__icon {
        background: #f0f6fc;
        padding: 16px;
        border-radius: 12px;
        color: #2271b1;
    }

    .wp-recruiterflow-intro__text h2 {
        margin: 0 0 12px;
        font-size: 1.3em;
        color: #1d2327;
    }

    .wp-recruiterflow-intro__text p {
        margin: 0;
        color: #50575e;
        font-size: 14px;
        line-height: 1.5;
    }

    .wp-recruiterflow-settings-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        padding: 24px;
    }

    .form-table {
        margin-top: 0;
    }

    .form-table th {
        padding-left: 0;
    }

    .regular-text {
        border-radius: 4px;
    }

    .button {
        border-radius: 4px;
    }

    .api-key-wrapper {
        display: flex;
        gap: 8px;
    }

    #toggle_api_key {
        padding: 4px 12px;
        height: auto;
    }

    .description {
        margin-top: 8px;
    }
</style>