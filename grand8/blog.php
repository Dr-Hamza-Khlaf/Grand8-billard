<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = 'Blog';
$pageDescription = current_lang() === 'fr'
    ? 'Conseils, actus et histoires autour du billard en Tunisie.'
    : 'Tips, updates, and stories around billiards in Tunisia.';
$currentPage = 'blog';

$posts = current_lang() === 'fr'
    ? [
        [
            'slug' => 'billiards-tunisia-scene',
            'title' => 'L’essor du billard en Tunisie',
            'excerpt' => 'Des salles de quartier aux classements nationaux, découvrez comment la scène tunisienne évolue.',
            'category' => 'Billard en Tunisie',
            'date' => '4 mai 2024',
            'description' => 'Comment les clubs locaux façonnent une nouvelle génération de joueurs.',
        ],
        [
            'slug' => 'championship-shot-routine',
            'title' => 'Une routine de tir de champion',
            'excerpt' => 'Construisez une routine régulière qui améliore la précision et la gestion du stress.',
            'category' => 'Entraînement & Conseils',
            'date' => '18 mai 2024',
            'description' => 'Une méthode pratique utilisée par les joueurs compétitifs de Grand 8.',
        ],
        [
            'slug' => 'grand-8-open-preview',
            'title' => 'Grand 8 Open : à quoi s’attendre',
            'excerpt' => 'Le tournoi phare revient. Voici le format, les récompenses et l’expérience joueur.',
            'category' => 'Tournois',
            'date' => '2 juin 2024',
            'description' => 'Un aperçu de notre tournoi signature avec les meilleurs talents.',
        ],
        [
            'slug' => 'new-coaching-program',
            'title' => 'Le Coaching Lab de Grand 8',
            'excerpt' => 'Coaching personnalisé, analyse vidéo et progression vers la compétition.',
            'category' => 'Actualités du club',
            'date' => '10 juin 2024',
            'description' => 'Une nouvelle initiative d’entraînement pour tous les niveaux.',
        ],
    ]
    : [
        [
            'slug' => 'billiards-tunisia-scene',
            'title' => 'The Rise of Billiards in Tunisia',
            'excerpt' => 'From neighborhood halls to national rankings, discover how the Tunisian billiard scene is evolving.',
            'category' => 'Billiard in Tunisia',
            'date' => 'May 4, 2024',
            'description' => 'How local clubs are reshaping the sport for a new generation of players.',
        ],
        [
            'slug' => 'championship-shot-routine',
            'title' => 'A Championship Shot Routine',
            'excerpt' => 'Build a consistent routine that improves accuracy and keeps your nerves steady in tournaments.',
            'category' => 'Training & Tips',
            'date' => 'May 18, 2024',
            'description' => 'A practical cue routine used by competitive players at Grand 8.',
        ],
        [
            'slug' => 'grand-8-open-preview',
            'title' => 'Grand 8 Open: What to Expect',
            'excerpt' => 'The flagship tournament is back. Here is the format, prizes, and player experience.',
            'category' => 'Tournaments',
            'date' => 'June 2, 2024',
            'description' => 'A preview of our signature tournament featuring top talent.',
        ],
        [
            'slug' => 'new-coaching-program',
            'title' => 'Introducing the Grand 8 Coaching Lab',
            'excerpt' => 'Personalized coaching plans, video analysis, and a pathway to competitive play.',
            'category' => 'Club Updates',
            'date' => 'June 10, 2024',
            'description' => 'A new training initiative for all skill levels.',
        ],
    ];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('blog.heading.eyebrow')) ?></p>
                <h2><?= e(t('blog.heading.title')) ?></h2>
                <p><?= e(t('blog.heading.description')) ?></p>
            </div>

            <div class="cards-grid three">
                <?php foreach ($posts as $post): ?>
                    <div class="card">
                        <div class="card-top">
                            <span><?= e($post['category']) ?></span>
                            <span><?= e($post['date']) ?></span>
                        </div>
                        <h3><?= e($post['title']) ?></h3>
                        <p class="meta-line"><?= e($post['excerpt']) ?></p>
                        <p class="meta-line" style="margin-top:12px;"><?= e($post['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>