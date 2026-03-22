<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = current_lang() === 'fr' ? 'Accueil' : 'Home';
$pageDescription = current_lang() === 'fr'
    ? 'Grand 8 Billiard Club à Bardo, Tunisie.'
    : 'Grand 8 Billiard Club in Bardo, Tunisia.';
$currentPage = 'home';

$stats = home_stats();
$posts = home_posts();
$upcomingEvents = home_upcoming_events(3);
$translations = load_translations(current_lang());

$whyItems = $translations['home']['why']['items'] ?? [];
$membershipItems = $translations['home']['membership']['items'] ?? [];
$competitionItems = $translations['home']['competition']['items'] ?? [];
$faqItems = $translations['home']['faq']['items'] ?? [];

$galleryImages = [
    'gallery/1.jpg',
    'gallery/2.jpg',
    'gallery/3.jpg',
    'gallery/4.jpg',
    'gallery/5.jpg',
    'gallery/6.jpg',
];

$partnerLogos = [
    'partners/1.png',
    'partners/2.jpg',
    'partners/3.jpg',
    'partners/4.png',
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <section class="hero-section">
        <div class="container hero-wrap">
            <div>
                <p class="eyebrow"><?= e(t('home.hero.eyebrow')) ?></p>
                <h1 class="hero-title">
                    <?= e(t('home.hero.titlePrefix')) ?>
                    <span><?= e(t('home.hero.titleAccent')) ?></span>
                </h1>
                <p class="hero-subtitle"><?= e(t('home.hero.subtitle')) ?></p>

                <div class="hero-actions">
                    <a href="<?= e(base_url('join.php')) ?>" class="btn btn-primary">
                        <?= e(t('home.hero.ctaJoin')) ?>
                    </a>
                    <a href="<?= e(base_url('events.php')) ?>" class="btn btn-outline">
                        <?= e(t('home.hero.ctaExplore')) ?>
                    </a>
                </div>
            </div>

            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                    <div class="card">
                        <div class="stat-value"><?= e((string) $stat['value']) ?></div>
                        <div class="stat-label"><?= e((string) $stat['label']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('home.eventsSection.eyebrow')) ?></p>
                <h2><?= e(t('home.eventsSection.title')) ?></h2>
                <p><?= e(t('home.eventsSection.description')) ?></p>
            </div>

            <div class="cards-grid three">
                <?php if (!empty($upcomingEvents)): ?>
                    <?php foreach ($upcomingEvents as $event): ?>
                        <?php
                        $slug = (string) ($event['slug'] ?? '');
                        $title = (string) ($event['title'] ?? '');
                        $category = (string) ($event['category'] ?? '');
                        $status = (string) ($event['status'] ?? '');
                        $date = (string) ($event['date'] ?? '');
                        $location = (string) ($event['location'] ?? 'Bardo, Tunisia');
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

                            <a
                                href="<?= e(base_url('event.php?slug=' . urlencode($slug))) ?>"
                                class="btn btn-outline"
                                style="margin-top:20px;"
                            >
                                <?= e(t('home.eventsSection.viewDetails')) ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card" style="grid-column: 1 / -1;">
                        <p class="muted"><?= e(t('home.eventsSection.empty')) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 24px;">
                <div class="cta-box">
                    <div class="split-grid" style="grid-template-columns: 1fr auto; align-items: center;">
                        <div>
                            <h2 style="margin: 0 0 14px; font-size: 2rem;">
                                <?= e(t('home.cta.title')) ?>
                            </h2>
                            <p class="muted" style="margin: 0;">
                                <?= e(t('home.cta.description')) ?>
                            </p>
                        </div>

                        <div class="cta-actions">
                            <a href="<?= e(base_url('contact.php')) ?>" class="btn btn-primary">
                                <?= e(t('home.cta.primaryLabel')) ?>
                            </a>
                            <a href="<?= e(base_url('events.php')) ?>" class="btn btn-outline">
                                <?= e(t('home.cta.secondaryLabel')) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('home.blogSection.eyebrow')) ?></p>
                <h2><?= e(t('home.blogSection.title')) ?></h2>
                <p><?= e(t('home.blogSection.description')) ?></p>
            </div>

            <div class="cards-grid three">
                <?php foreach ($posts as $post): ?>
                    <div class="card">
                        <div class="card-top">
                            <span><?= e((string) $post['category']) ?></span>
                            <span><?= e((string) $post['date']) ?></span>
                        </div>

                        <h3><?= e((string) $post['title']) ?></h3>
                        <p class="meta-line"><?= e((string) $post['excerpt']) ?></p>

                        <a href="<?= e(base_url('blog.php')) ?>" class="card-link">
                            <?= e(t('home.blogSection.readMore')) ?> →
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('home.why.eyebrow')) ?></p>
                <h2><?= e(t('home.why.title')) ?></h2>
                <p><?= e(t('home.why.description')) ?></p>
            </div>

            <div class="why-grid">
                <?php foreach ($whyItems as $item): ?>
                    <div class="card">
                        <h3><?= e((string) $item['title']) ?></h3>
                        <p class="meta-line"><?= e((string) $item['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container split-grid">
            <div class="panel">
                <h2 style="margin-top: 0;"><?= e(t('home.membership.title')) ?></h2>

                <ul class="membership-list">
                    <?php foreach ($membershipItems as $item): ?>
                        <li>
                            <strong><?= e((string) $item['label']) ?></strong>
                            — <?= e((string) $item['text']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="panel">
                <h2 style="margin-top: 0;"><?= e(t('home.training.title')) ?></h2>
                <p class="meta-line"><?= e(t('home.training.description')) ?></p>
                <a href="<?= e(base_url('join.php')) ?>" class="card-link">
                    <?= e(t('home.training.cta')) ?> →
                </a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('home.competition.eyebrow')) ?></p>
                <h2><?= e(t('home.competition.title')) ?></h2>
                <p><?= e(t('home.competition.description')) ?></p>
            </div>

            <div class="cards-grid three">
                <?php foreach ($competitionItems as $item): ?>
                    <div class="card">
                        <h3><?= e((string) $item['title']) ?></h3>
                        <p class="meta-line"><?= e((string) $item['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('home.gallery.eyebrow')) ?></p>
                <h2><?= e(t('home.gallery.title')) ?></h2>
                <p><?= e(t('home.gallery.description')) ?></p>
            </div>

            <div class="gallery-grid">
                <?php foreach ($galleryImages as $img): ?>
                    <div class="gallery-item">
                        <img src="<?= e(asset_url('images/' . $img)) ?>" alt="Grand 8 gallery">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('home.sponsors.eyebrow')) ?></p>
                <h2><?= e(t('home.sponsors.title')) ?></h2>
                <p><?= e(t('home.sponsors.description')) ?></p>
            </div>

            <div class="sponsors-grid">
                <?php foreach ($partnerLogos as $logo): ?>
                    <div class="sponsor-item">
                        <img src="<?= e(asset_url('images/' . $logo)) ?>" alt="Partner logo">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" style="padding-bottom: 72px;">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('home.faq.eyebrow')) ?></p>
                <h2><?= e(t('home.faq.title')) ?></h2>
                <p><?= e(t('home.faq.description')) ?></p>
            </div>

            <div class="faq-grid">
                <?php foreach ($faqItems as $item): ?>
                    <div class="card">
                        <h3><?= e((string) $item['q']) ?></h3>
                        <p class="meta-line"><?= e((string) $item['a']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>