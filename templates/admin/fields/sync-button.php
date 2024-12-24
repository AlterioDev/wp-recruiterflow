<div class="sync-controls">
    <div>
        <button type="button" id="sync-vacancies" class="button button-primary">
            <?php esc_html_e('Sync Now', 'wp-recruiterflow'); ?>
        </button>
        <span class="spinner"></span>
    </div>
    <div id="sync-status" class="sync-status"></div>
</div>

<style>
    .sync-controls {
        margin-top: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        flex-direction: column;
    }

    .sync-controls>div {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .sync-controls .spinner {
        float: none;
        margin: 0;
    }

    .sync-status {
        font-style: italic;
    }

    .sync-status.error {
        color: #d63638;
    }

    .sync-status.success {
        color: #00a32a;
    }
</style>

<script>
    document.getElementById('sync-vacancies').addEventListener('click', async function() {
        const button = this;
        const spinner = button.nextElementSibling;
        const status = document.getElementById('sync-status');

        button.disabled = true;
        spinner.style.visibility = 'visible';
        status.textContent = '<?php esc_js(__('Syncing vacancies...', 'wp-recruiterflow')); ?>';
        status.className = 'sync-status';

        try {
            const response = await fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'wp_recruiterflow_sync',
                    nonce: '<?php echo esc_js($nonce); ?>'
                })
            });

            const data = await response.json();

            if (data.success) {
                status.textContent = data.data.message;
                status.classList.add('success');
            } else {
                throw new Error(data.data.message);
            }
        } catch (error) {
            status.textContent = error.message;
            status.classList.add('error');
        } finally {
            button.disabled = false;
            spinner.style.visibility = 'hidden';
        }
    });
</script>