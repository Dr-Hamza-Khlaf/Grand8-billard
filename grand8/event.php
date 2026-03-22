<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = current_lang() === 'fr' ? 'Détail événement' : 'Event details';
$pageDescription = current_lang() === 'fr'
    ? 'Consultez les détails de l’événement Grand 8.'
    : 'View Grand 8 event details.';
$currentPage = 'events';

$slug = trim((string)($_GET['slug'] ?? ''));

if ($slug === '') {
    http_response_code(404);
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <main>
        <section class="section">
            <div class="container">
                <div class="card">
                    <h2><?= current_lang() === 'fr' ? 'Événement introuvable' : 'Event not found' ?></h2>
                    <p class="meta-line">
                        <?= current_lang() === 'fr'
                            ? 'Aucun slug fourni.'
                            : 'No event slug was provided.' ?>
                    </p>
                </div>
            </div>
        </section>
    </main>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$event = null;
$events = fetch_events(['active' => '1']);

foreach ($events as $item) {
    if ((string)($item['slug'] ?? '') === $slug) {
        $event = $item;
        break;
    }
}

if (!$event) {
    http_response_code(404);
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <main>
        <section class="section">
            <div class="container">
                <div class="card">
                    <h2><?= current_lang() === 'fr' ? 'Événement introuvable' : 'Event not found' ?></h2>
                    <p class="meta-line">
                        <?= current_lang() === 'fr'
                            ? 'Cet événement n’existe pas ou n’est plus disponible.'
                            : 'This event does not exist or is no longer available.' ?>
                    </p>
                    <a href="<?= e(base_url('events.php')) ?>" class="btn btn-outline" style="margin-top:20px;">
                        <?= e(t('nav.events')) ?>
                    </a>
                </div>
            </div>
        </section>
    </main>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$title = (string)($event['title'] ?? '');
$category = (string)($event['category'] ?? '');
$status = (string)($event['status'] ?? '');
$date = (string)($event['date'] ?? '');
$location = (string)($event['location'] ?? 'Bardo, Tunisia');
$description = (string)($event['description'] ?? '');
$isUpcoming = is_upcoming_event($event);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e($category) ?></p>
                <h2><?= e($title) ?></h2>
                <p><?= e($date) ?> • <?= e($location) ?></p>
            </div>

            <div class="panel">
                <div class="card-top" style="margin-bottom: 18px;">
                    <span><?= e($category) ?></span>
                    <span class="status-pill"><?= e($status) ?></span>
                </div>

                <div style="display:grid; gap:24px;">
                    <div>
                        <h3 style="margin-top:0;">
                            <?= current_lang() === 'fr' ? 'Description' : 'Description' ?>
                        </h3>
                        <p class="meta-line" style="font-size:1rem; line-height:1.8;">
                            <?= nl2br(e($description)) ?>
                        </p>
                    </div>

                    <div class="split-grid" style="grid-template-columns: 1fr 1fr;">
                        <div class="card" style="padding:20px;">
                            <h3 style="margin-top:0; font-size:1rem;">
                                <?= current_lang() === 'fr' ? 'Date' : 'Date' ?>
                            </h3>
                            <p class="meta-line"><?= e($date) ?></p>
                        </div>

                        <div class="card" style="padding:20px;">
                            <h3 style="margin-top:0; font-size:1rem;">
                                <?= current_lang() === 'fr' ? 'Lieu' : 'Location' ?>
                            </h3>
                            <p class="meta-line"><?= e($location) ?></p>
                        </div>
                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:12px;">
                        <?php if ($isUpcoming): ?>
                            <a href="<?= e(base_url('register.php?slug=' . urlencode($slug))) ?>" class="btn btn-primary">
                                <?= current_lang() === 'fr' ? 'S’inscrire à l’événement' : 'Register for event' ?>
                            </a>
                        <?php else: ?>
                            <span class="btn btn-outline" style="opacity:.65; cursor:default;">
                                <?= current_lang() === 'fr' ? 'Inscriptions fermées' : 'Registration closed' ?>
                            </span>
                        <?php endif; ?>

                        <a href="<?= e(base_url('events.php')) ?>" class="btn btn-outline">
                            <?= current_lang() === 'fr' ? 'Retour aux événements' : 'Back to events' ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>