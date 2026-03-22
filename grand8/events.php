<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = current_lang() === 'fr' ? 'Événements' : 'Events';
$pageDescription = current_lang() === 'fr'
    ? 'Découvrez les tournois, ligues et événements de Grand 8.'
    : 'Explore Grand 8 tournaments, leagues, and events.';
$currentPage = 'events';

$translations = load_translations(current_lang());

$allLabel = $translations['eventsExplorer']['filters']['all'] ?? 'All';
$upcomingLabel = $translations['eventsExplorer']['filters']['upcoming'] ?? 'Upcoming';
$pastLabel = $translations['eventsExplorer']['filters']['past'] ?? 'Past';
$tournamentLabel = $translations['eventsExplorer']['filters']['tournament'] ?? 'Tournament';
$friendlyLabel = $translations['eventsExplorer']['filters']['friendly'] ?? 'Friendly';

$filter = strtolower(trim((string)($_GET['filter'] ?? 'all')));

$allowedFilters = ['all', 'upcoming', 'past', 'tournament', 'friendly'];
if (!in_array($filter, $allowedFilters, true)) {
    $filter = 'all';
}

$events = fetch_events(['active' => '1']);

usort($events, static function (array $a, array $b): int {
    $dateA = strtotime((string)($a['event_date'] ?? '1970-01-01')) ?: 0;
    $dateB = strtotime((string)($b['event_date'] ?? '1970-01-01')) ?: 0;
    return $dateB <=> $dateA;
});

$filteredEvents = array_values(array_filter($events, static function (array $event) use ($filter): bool {
    $status = strtolower(trim((string)($event['status'] ?? '')));
    $category = strtolower(trim((string)($event['category'] ?? '')));

    return match ($filter) {
        'upcoming' => is_upcoming_event($event),
        'past' => $status === 'past' || !is_upcoming_event($event),
        'tournament' => $category === 'tournament',
        'friendly' => $category === 'friendly',
        default => true,
    };
}));

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('events.heading.eyebrow')) ?></p>
                <h2><?= e(t('events.heading.title')) ?></h2>
                <p><?= e(t('events.heading.description')) ?></p>
            </div>

            <div class="events-filters" style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom: 28px;">
                <a href="<?= e(base_url('events.php?filter=all')) ?>" class="btn <?= $filter === 'all' ? 'btn-primary' : 'btn-outline' ?>">
                    <?= e($allLabel) ?>
                </a>
                <a href="<?= e(base_url('events.php?filter=upcoming')) ?>" class="btn <?= $filter === 'upcoming' ? 'btn-primary' : 'btn-outline' ?>">
                    <?= e($upcomingLabel) ?>
                </a>
                <a href="<?= e(base_url('events.php?filter=past')) ?>" class="btn <?= $filter === 'past' ? 'btn-primary' : 'btn-outline' ?>">
                    <?= e($pastLabel) ?>
                </a>
                <a href="<?= e(base_url('events.php?filter=tournament')) ?>" class="btn <?= $filter === 'tournament' ? 'btn-primary' : 'btn-outline' ?>">
                    <?= e($tournamentLabel) ?>
                </a>
                <a href="<?= e(base_url('events.php?filter=friendly')) ?>" class="btn <?= $filter === 'friendly' ? 'btn-primary' : 'btn-outline' ?>">
                    <?= e($friendlyLabel) ?>
                </a>
            </div>

            <div class="cards-grid three">
                <?php if (!empty($filteredEvents)): ?>
                    <?php foreach ($filteredEvents as $event): ?>
                        <?php
                        $slug = (string)($event['slug'] ?? '');
                        $title = (string)($event['title'] ?? '');
                        $category = (string)($event['category'] ?? '');
                        $status = (string)($event['status'] ?? '');
                        $date = (string)($event['date'] ?? '');
                        $location = (string)($event['location'] ?? 'Bardo, Tunisia');
                        $isUpcoming = is_upcoming_event($event);
                        ?>
                        <div class="card">
                            <div class="card-top">
                                <span><?= e($category) ?></span>
                                <span class="status-pill"><?= e($status) ?></span>
                            </div>

                            <h3><?= e($title) ?></h3>

                            <p class="meta-line">
                                <?= e($date) ?> • <?= e($location) ?>
                            </p>

                            <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:20px;">
                                <a href="<?= e(base_url('event.php?slug=' . urlencode($slug))) ?>" class="btn btn-outline">
                                    <?= e(t('home.eventsSection.viewDetails')) ?>
                                </a>

                                <?php if ($isUpcoming): ?>
                                    <a href="<?= e(base_url('register.php?slug=' . urlencode($slug))) ?>" class="btn btn-primary">
                                        <?= e($translations['eventsExplorer']['modal']['register'] ?? 'Register for event') ?>
                                    </a>
                                <?php else: ?>
                                    <span class="btn btn-outline" style="opacity:.65; cursor:default;">
                                        <?= e($translations['eventsExplorer']['modal']['closed'] ?? 'Registration closed') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card" style="grid-column: 1 / -1;">
                        <p class="muted">
                            <?= current_lang() === 'fr'
                                ? 'Aucun événement trouvé pour ce filtre.'
                                : 'No events found for this filter.' ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 32px;">
                <div class="cta-box">
                    <div class="split-grid" style="grid-template-columns: 1fr auto; align-items: center;">
                        <div>
                            <h2 style="margin:0 0 14px; font-size:2rem;"><?= e(t('events.cta.title')) ?></h2>
                            <p class="muted" style="margin:0;"><?= e(t('events.cta.description')) ?></p>
                        </div>
                        <div class="cta-actions">
                            <a href="<?= e(base_url('contact.php')) ?>" class="btn btn-primary">
                                <?= e(t('events.cta.primaryLabel')) ?>
                            </a>
                            <a href="<?= e(base_url('join.php')) ?>" class="btn btn-outline">
                                <?= e(t('events.cta.secondaryLabel')) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>