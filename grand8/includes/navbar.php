<?php
declare(strict_types=1);

$currentPage = $currentPage ?? 'home';
?>
<header class="site-header">
    <nav class="container navbar">
        <a href="<?= e(base_url()) ?>" class="brand">
            Grand <span>8</span>
        </a>

        <button
            class="menu-toggle"
            type="button"
            id="menuToggle"
            aria-label="Toggle menu"
            aria-controls="navPanel"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
        </button>

        <div class="nav-panel" id="navPanel">
            <div class="nav-links">
                <a href="<?= e(base_url()) ?>" class="<?= e(nav_current('home', $currentPage)) ?>">
                    <?= e(t('nav.home')) ?>
                </a>
                <a href="<?= e(base_url('club.php')) ?>" class="<?= e(nav_current('club', $currentPage)) ?>">
                    <?= e(t('nav.club')) ?>
                </a>
                <a href="<?= e(base_url('events.php')) ?>" class="<?= e(nav_current('events', $currentPage)) ?>">
                    <?= e(t('nav.events')) ?>
                </a>
                <a href="<?= e(base_url('blog.php')) ?>" class="<?= e(nav_current('blog', $currentPage)) ?>">
                    <?= e(t('nav.blog')) ?>
                </a>
                <a href="<?= e(base_url('join.php')) ?>" class="<?= e(nav_current('join', $currentPage)) ?>">
                    <?= e(t('nav.join')) ?>
                </a>
                <a href="<?= e(base_url('contact.php')) ?>" class="<?= e(nav_current('contact', $currentPage)) ?>">
                    <?= e(t('nav.contact')) ?>
                </a>
            </div>

            <div class="nav-actions">
                <div class="lang-switch" aria-label="<?= e(t('common.language')) ?>">
                    <a
                        href="<?= e(switch_lang_url('fr')) ?>"
                        class="<?= current_lang() === 'fr' ? 'active' : '' ?>"
                    >
                        FR
                    </a>
                    <a
                        href="<?= e(switch_lang_url('en')) ?>"
                        class="<?= current_lang() === 'en' ? 'active' : '' ?>"
                    >
                        EN
                    </a>
                </div>

                <a href="<?= e(base_url('join.php')) ?>" class="btn btn-primary">
                    <?= e(t('nav.joinCta')) ?>
                </a>
            </div>
        </div>
    </nav>
</header>