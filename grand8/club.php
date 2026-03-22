<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = current_lang() === 'fr' ? 'Club' : 'Club';
$pageDescription = current_lang() === 'fr'
    ? 'Découvrez le club Grand 8, ses installations et ses services.'
    : 'Discover the Grand 8 club, facilities, and services.';
$currentPage = 'club';

$translations = load_translations(current_lang());

$values = $translations['club']['story']['values'] ?? [];
$hours = $translations['club']['hours']['items'] ?? [];
$facilities = $translations['club']['facilities']['items'] ?? [];
$rules = $translations['club']['rules']['items'] ?? [];
$services = $translations['club']['services']['items'] ?? [];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('club.heading.eyebrow')) ?></p>
                <h2><?= e(t('club.heading.title')) ?></h2>
                <p><?= e(t('club.heading.description')) ?></p>
            </div>

            <div class="panel">
                <h3 style="margin-top:0;"><?= e(t('club.story.title')) ?></h3>
                <p class="meta-line" style="font-size:1rem; line-height:1.8;">
                    <?= e(t('club.story.description')) ?>
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cards-grid three">
                <?php foreach ($values as $item): ?>
                    <div class="card">
                        <h3><?= e((string)$item['title']) ?></h3>
                        <p class="meta-line"><?= e((string)$item['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container split-grid">
            <div class="panel">
                <h3 style="margin-top:0;"><?= e(t('club.hours.title')) ?></h3>
                <ul class="membership-list">
                    <?php foreach ($hours as $item): ?>
                        <li><?= e((string)$item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="panel">
                <h3 style="margin-top:0;"><?= e(t('club.facilities.title')) ?></h3>
                <ul class="membership-list">
                    <?php foreach ($facilities as $item): ?>
                        <li><?= e((string)$item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container split-grid">
            <div class="panel">
                <h3 style="margin-top:0;"><?= e(t('club.rules.title')) ?></h3>
                <ul class="membership-list">
                    <?php foreach ($rules as $item): ?>
                        <li><?= e((string)$item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="panel">
                <h3 style="margin-top:0;"><?= e(t('club.services.title')) ?></h3>
                <ul class="membership-list">
                    <?php foreach ($services as $item): ?>
                        <li><?= e((string)$item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

    <section class="section" style="padding-bottom:72px;">
        <div class="container">
            <div class="cta-box">
                <div class="split-grid" style="grid-template-columns: 1fr auto; align-items:center;">
                    <div>
                        <h2 style="margin:0 0 14px; font-size:2rem;"><?= e(t('club.cta.title')) ?></h2>
                        <p class="muted" style="margin:0;"><?= e(t('club.cta.description')) ?></p>
                    </div>
                    <div class="cta-actions">
                        <a href="<?= e(base_url('join.php')) ?>" class="btn btn-primary">
                            <?= e(t('club.cta.primaryLabel')) ?>
                        </a>
                        <a href="<?= e(base_url('contact.php')) ?>" class="btn btn-outline">
                            <?= e(t('club.cta.secondaryLabel')) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>