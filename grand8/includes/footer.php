<?php
declare(strict_types=1);

$newsletterSuccessMessage = null;
$newsletterErrorMessage = null;

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['newsletter_form']) &&
    $_POST['newsletter_form'] === '1'
) {
    $newsletterPayload = [
        'company' => trim((string)($_POST['company'] ?? '')),
        'email' => trim((string)($_POST['newsletter_email'] ?? '')),
    ];

    if ($newsletterPayload['email'] === '') {
        $newsletterErrorMessage = current_lang() === 'fr'
            ? 'Veuillez saisir votre email.'
            : 'Please enter your email.';
    } else {
        $apiUrl = 'http://localhost/grand8-admin/api/newsletter-subscribe.php';

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
                'content' => http_build_query($newsletterPayload),
                'timeout' => 15,
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($apiUrl, false, $context);

        if ($response === false) {
            $newsletterErrorMessage = current_lang() === 'fr'
                ? 'Impossible de vous abonner pour le moment.'
                : 'Unable to subscribe at the moment.';
        } else {
            $data = json_decode($response, true);

            if (is_array($data) && !empty($data['success'])) {
                $newsletterSuccessMessage = is_string($data['message'] ?? null)
                    ? (string)$data['message']
                    : (current_lang() === 'fr'
                        ? 'Inscription réussie.'
                        : 'Subscription successful.');
                $_POST['newsletter_email'] = '';
            } else {
                $newsletterErrorMessage = is_array($data) && !empty($data['message'])
                    ? (string)$data['message']
                    : (current_lang() === 'fr'
                        ? 'Une erreur est survenue lors de l’inscription.'
                        : 'An error occurred while subscribing.');
            }
        }
    }
}
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-col">
            <p class="footer-brand">Grand <span>8</span></p>
            <p class="footer-text"><?= e(t('footer.description')) ?></p>

          <div class="social-links">
    <a href="https://www.facebook.com/BillardGrand8" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="social-icon">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.6-1.5H16.7V4c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.4V10H7v3h3V21h3.5z"/>
        </svg>
    </a>

    <a href="https://www.instagram.com/assogrand8/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="social-icon">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M7.5 3h9A4.5 4.5 0 0 1 21 7.5v9a4.5 4.5 0 0 1-4.5 4.5h-9A4.5 4.5 0 0 1 3 16.5v-9A4.5 4.5 0 0 1 7.5 3zm0 1.8A2.7 2.7 0 0 0 4.8 7.5v9a2.7 2.7 0 0 0 2.7 2.7h9a2.7 2.7 0 0 0 2.7-2.7v-9a2.7 2.7 0 0 0-2.7-2.7h-9zm9.45 1.35a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5zM12 7.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm0 1.8A2.7 2.7 0 1 0 14.7 12 2.7 2.7 0 0 0 12 9.3z"/>
        </svg>
    </a>

    <a href="https://www.tiktok.com/@billard_g8?_r=1&_t=ZM-92zRbnS3Bdc" target="_blank" rel="noopener noreferrer" aria-label="TikTok" class="social-icon">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M14.5 3c.5 1.7 1.5 3 3.2 3.8.8.4 1.5.6 2.3.7v2.8c-1-.1-1.9-.4-2.8-.8-.6-.3-1.2-.7-1.7-1.1v6.2a5.8 5.8 0 1 1-5.8-5.8c.3 0 .6 0 .9.1v2.9a2.8 2.8 0 1 0 2 2.7V3h1.9z"/>
        </svg>
    </a>

    <a href="https://www.youtube.com/@associationtunisiennedebil8677" target="_blank" rel="noopener noreferrer" aria-label="YouTube" class="social-icon">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M21.6 7.2a2.9 2.9 0 0 0-2-2C17.9 4.7 12 4.7 12 4.7s-5.9 0-7.6.5a2.9 2.9 0 0 0-2 2A30.7 30.7 0 0 0 2 12a30.7 30.7 0 0 0 .4 4.8 2.9 2.9 0 0 0 2 2c1.7.5 7.6.5 7.6.5s5.9 0 7.6-.5a2.9 2.9 0 0 0 2-2A30.7 30.7 0 0 0 22 12a30.7 30.7 0 0 0-.4-4.8zM10 15.5v-7l6 3.5-6 3.5z"/>
        </svg>
    </a>
</div>
        </div>

        <div class="footer-col">
            <h3><?= e(t('footer.explore')) ?></h3>
            <a href="<?= e(base_url('club.php')) ?>"><?= e(t('footer.clubInfo')) ?></a>
            <a href="<?= e(base_url('events.php')) ?>"><?= e(t('footer.events')) ?></a>
            <a href="<?= e(base_url('blog.php')) ?>"><?= e(t('footer.blog')) ?></a>
            <a href="<?= e(base_url('join.php')) ?>"><?= e(t('footer.join')) ?></a>
        </div>

        <div class="footer-col">
            <h3><?= e(t('footer.contact')) ?></h3>
            <p><?= e(SITE_ADDRESS) ?></p>
            <p><?= e(SITE_PHONE) ?></p>
            <p><?= e(SITE_EMAIL) ?></p>
            <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(t('footer.emailUs')) ?></a>
        </div>

        <div class="footer-col">
            <h3><?= e(t('footer.newsletter')) ?></h3>
            <p class="footer-text"><?= e(t('footer.newsletterText')) ?></p>

            <?php if ($newsletterSuccessMessage): ?>
                <div class="card" style="padding:16px; margin-bottom:14px;">
                    <p class="meta-line" style="margin:0; color:#4ade80;">
                        <?= e($newsletterSuccessMessage) ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($newsletterErrorMessage): ?>
                <div class="card" style="padding:16px; margin-bottom:14px;">
                    <p class="meta-line" style="margin:0; color:#f87171;">
                        <?= e($newsletterErrorMessage) ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="newsletter-form" method="post">
                <input type="hidden" name="newsletter_form" value="1">
                <input type="hidden" name="company" value="">
                <input
                    type="email"
                    name="newsletter_email"
                    placeholder="Email"
                    aria-label="Email"
                    value="<?= e((string)($_POST['newsletter_email'] ?? '')) ?>"
                    required
                >
                <button type="submit" class="btn btn-primary">OK</button>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        © <?= date('Y') ?>
        <a href="https://legacy4you.agency" target="_blank" rel="noopener noreferrer">Legacy 4 You</a>.
        <?= e(t('footer.copyright')) ?>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const navPanel = document.getElementById('navPanel');

    if (menuToggle && navPanel) {
        menuToggle.addEventListener('click', function () {
            const isOpen = navPanel.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }
});
</script>
</div>
</body>
</html>