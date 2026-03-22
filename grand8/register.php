<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = current_lang() === 'fr' ? 'Inscription événement' : 'Event registration';
$pageDescription = current_lang() === 'fr'
    ? 'Inscrivez-vous à un événement Grand 8.'
    : 'Register for a Grand 8 event.';
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
                        <?= current_lang() === 'fr' ? 'Aucun slug fourni.' : 'No event slug was provided.' ?>
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
$date = (string)($event['date'] ?? '');
$location = (string)($event['location'] ?? 'Bardo, Tunisia');
$isUpcoming = is_upcoming_event($event);

$successMessage = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isUpcoming) {
    $payload = [
        'company' => trim((string)($_POST['company'] ?? '')),
        'eventId' => (string)($event['id'] ?? ''),
        'fullName' => trim((string)($_POST['fullName'] ?? '')),
        'phone' => trim((string)($_POST['phone'] ?? '')),
        'email' => trim((string)($_POST['email'] ?? '')),
        'address' => trim((string)($_POST['address'] ?? '')),
        'clubName' => trim((string)($_POST['clubName'] ?? '')),
        'level' => trim((string)($_POST['level'] ?? 'Beginner')),
        'notes' => trim((string)($_POST['notes'] ?? '')),
    ];

    if (
        $payload['fullName'] === '' ||
        $payload['phone'] === '' ||
        $payload['email'] === '' ||
        $payload['address'] === '' ||
        $payload['clubName'] === '' ||
        $payload['level'] === ''
    ) {
        $errorMessage = current_lang() === 'fr'
            ? 'Veuillez remplir tous les champs obligatoires.'
            : 'Please fill in all required fields.';
    } else {
        $apiUrl = 'http://localhost/grand8-admin/api/event-register.php';

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
                'content' => http_build_query($payload),
                'timeout' => 15,
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($apiUrl, false, $context);

        if ($response === false) {
            $errorMessage = current_lang() === 'fr'
                ? 'Impossible d’envoyer l’inscription pour le moment.'
                : 'Unable to submit the registration at the moment.';
        } else {
            $data = json_decode($response, true);

            if (is_array($data) && !empty($data['success'])) {
                $successMessage = current_lang() === 'fr'
                    ? 'Inscription envoyée avec succès.'
                    : 'Registration submitted successfully.';
                $_POST = [];
            } else {
                $errorMessage = is_array($data) && !empty($data['message'])
                    ? (string)$data['message']
                    : (current_lang() === 'fr'
                        ? 'Une erreur est survenue lors de l’envoi.'
                        : 'An error occurred while submitting.');
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <section class="section">
        <div class="container" style="max-width: 860px;">
            <div class="section-heading">
                <p class="eyebrow"><?= e(t('eventRegister.heading.eyebrow')) ?></p>
                <h2><?= e(t('eventRegister.heading.title')) ?></h2>
                <p><?= e($title) ?> • <?= e($date) ?> • <?= e($location) ?></p>
            </div>

            <div class="panel">
                <?php if (!$isUpcoming): ?>
                    <div class="card" style="padding:20px;">
                        <h3 style="margin-top:0;"><?= e(t('eventRegister.closed.title')) ?></h3>
                        <p class="meta-line"><?= e(t('eventRegister.closed.description')) ?></p>
                    </div>
                <?php else: ?>
                    <?php if ($successMessage): ?>
                        <div class="card" style="padding:20px; margin-bottom:20px;">
                            <h3 style="margin-top:0; color:#4ade80;">
                                <?= current_lang() === 'fr' ? 'Succès' : 'Success' ?>
                            </h3>
                            <p class="meta-line"><?= e($successMessage) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($errorMessage): ?>
                        <div class="card" style="padding:20px; margin-bottom:20px;">
                            <h3 style="margin-top:0; color:#f87171;">
                                <?= current_lang() === 'fr' ? 'Erreur' : 'Error' ?>
                            </h3>
                            <p class="meta-line"><?= e($errorMessage) ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="register-form" style="display:grid; gap:18px;">
                        <input type="hidden" name="company" value="">

                        <div class="split-grid" style="grid-template-columns:1fr 1fr;">
                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    <?= current_lang() === 'fr' ? 'Nom complet' : 'Full name' ?>
                                </label>
                                <input
                                    type="text"
                                    name="fullName"
                                    class="form-control"
                                    required
                                    value="<?= e((string)($_POST['fullName'] ?? '')) ?>"
                                >
                            </div>

                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    <?= current_lang() === 'fr' ? 'Téléphone' : 'Phone' ?>
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    required
                                    value="<?= e((string)($_POST['phone'] ?? '')) ?>"
                                >
                            </div>
                        </div>

                        <div class="split-grid" style="grid-template-columns:1fr 1fr;">
                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required
                                    value="<?= e((string)($_POST['email'] ?? '')) ?>"
                                >
                            </div>

                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    <?= current_lang() === 'fr' ? 'Adresse' : 'Address' ?>
                                </label>
                                <input
                                    type="text"
                                    name="address"
                                    class="form-control"
                                    required
                                    value="<?= e((string)($_POST['address'] ?? '')) ?>"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="stat-label" style="display:block; margin-bottom:8px;">
                                <?= current_lang() === 'fr' ? 'Nom du club' : 'Club name' ?>
                            </label>
                            <input
                                type="text"
                                name="clubName"
                                class="form-control"
                                required
                                value="<?= e((string)($_POST['clubName'] ?? '')) ?>"
                            >
                        </div>

                        <div>
                            <label class="stat-label" style="display:block; margin-bottom:8px;">
                                <?= current_lang() === 'fr' ? 'Niveau' : 'Level' ?>
                            </label>
                            <select name="level" class="form-control" required>
                                <option value="Beginner" <?= (($_POST['level'] ?? 'Beginner') === 'Beginner') ? 'selected' : '' ?>>
                                    <?= current_lang() === 'fr' ? 'Débutant' : 'Beginner' ?>
                                </option>
                                <option value="Intermediate" <?= (($_POST['level'] ?? '') === 'Intermediate') ? 'selected' : '' ?>>
                                    <?= current_lang() === 'fr' ? 'Intermédiaire' : 'Intermediate' ?>
                                </option>
                                <option value="Advanced" <?= (($_POST['level'] ?? '') === 'Advanced') ? 'selected' : '' ?>>
                                    <?= current_lang() === 'fr' ? 'Avancé' : 'Advanced' ?>
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="stat-label" style="display:block; margin-bottom:8px;">
                                <?= current_lang() === 'fr' ? 'Notes' : 'Notes' ?>
                            </label>
                            <textarea name="notes" class="form-control" rows="5"><?= e((string)($_POST['notes'] ?? '')) ?></textarea>
                        </div>

                        <div style="display:flex; flex-wrap:wrap; gap:12px;">
                            <button type="submit" class="btn btn-primary">
                                <?= current_lang() === 'fr' ? 'Envoyer l’inscription' : 'Submit registration' ?>
                            </button>

                            <a href="<?= e(base_url('event.php?slug=' . urlencode($slug))) ?>" class="btn btn-outline">
                                <?= current_lang() === 'fr' ? 'Retour à l’événement' : 'Back to event' ?>
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>