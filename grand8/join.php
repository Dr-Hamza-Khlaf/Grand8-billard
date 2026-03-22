<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = current_lang() === 'fr' ? 'Adhésion' : 'Join';
$pageDescription = current_lang() === 'fr'
    ? 'Rejoignez le club Grand 8.'
    : 'Join the Grand 8 club.';
$currentPage = 'join';

$translations = load_translations(current_lang());
$joinWhyItems = $translations['join']['why']['items'] ?? [];

$successMessage = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = [
        'company' => trim((string)($_POST['company'] ?? '')),
        'fullName' => trim((string)($_POST['fullName'] ?? '')),
        'age' => trim((string)($_POST['age'] ?? '')),
        'city' => trim((string)($_POST['city'] ?? '')),
        'phone' => trim((string)($_POST['phone'] ?? '')),
        'email' => trim((string)($_POST['email'] ?? '')),
        'applyAs' => trim((string)($_POST['applyAs'] ?? '')),
        'experienceLevel' => trim((string)($_POST['experienceLevel'] ?? '')),
        'preferredDiscipline' => trim((string)($_POST['preferredDiscipline'] ?? '')),
        'availability' => trim((string)($_POST['availability'] ?? '')),
        'motivationMessage' => trim((string)($_POST['motivationMessage'] ?? '')),
        'consentContact' => isset($_POST['consentContact']) ? '1' : '0',
    ];

    if (
        $payload['fullName'] === '' ||
        $payload['phone'] === '' ||
        $payload['email'] === ''
    ) {
        $errorMessage = current_lang() === 'fr'
            ? 'Veuillez remplir les champs obligatoires.'
            : 'Please fill in the required fields.';
    } else {
        $apiUrl = 'http://localhost/grand8-admin/api/join-request.php';

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
                ? 'Impossible d’envoyer la demande pour le moment.'
                : 'Unable to submit the request at the moment.';
        } else {
            $data = json_decode($response, true);

            if (is_array($data) && !empty($data['success'])) {
                $successMessage = current_lang() === 'fr'
                    ? 'Demande envoyée avec succès.'
                    : 'Request submitted successfully.';
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
        <div class="container" style="max-width: 1120px;">
            <div class="section-heading" style="margin-bottom: 34px;">
                <p class="eyebrow"><?= e(t('join.heading.eyebrow')) ?></p>
                <h2><?= e(t('join.heading.title')) ?></h2>
                <p><?= e(t('join.heading.description')) ?></p>
            </div>

            <div class="split-grid join-layout" style="grid-template-columns: 1.18fr 0.82fr; align-items:start; gap:32px;">
                <div class="panel">
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

                    <form method="post" class="join-form" style="display:grid; gap:18px;">
                        <input type="hidden" name="company" value="">

                        <div class="split-grid" style="grid-template-columns:1fr 1fr; gap:14px;">
                            <div>
                                <label class="form-label">
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
                                <label class="form-label">
                                    <?= current_lang() === 'fr' ? 'Âge' : 'Age' ?>
                                </label>
                                <input
                                    type="number"
                                    name="age"
                                    class="form-control"
                                    value="<?= e((string)($_POST['age'] ?? '')) ?>"
                                >
                            </div>
                        </div>

                        <div class="split-grid" style="grid-template-columns:1fr 1fr; gap:14px;">
                            <div>
                                <label class="form-label">
                                    <?= current_lang() === 'fr' ? 'Ville' : 'City' ?>
                                </label>
                                <input
                                    type="text"
                                    name="city"
                                    class="form-control"
                                    value="<?= e((string)($_POST['city'] ?? '')) ?>"
                                >
                            </div>

                            <div>
                                <label class="form-label">
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

                        <div class="split-grid" style="grid-template-columns:1fr 1fr; gap:14px;">
                            <div>
                                <label class="form-label">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required
                                    value="<?= e((string)($_POST['email'] ?? '')) ?>"
                                >
                            </div>

                            <div>
                                <label class="form-label">
                                    <?= current_lang() === 'fr' ? 'Candidature en tant que' : 'Apply as' ?>
                                </label>
                                <select name="applyAs" class="form-control">
                                    <option value=""><?= current_lang() === 'fr' ? 'Choisir' : 'Choose' ?></option>
                                    <option value="Player" <?= (($_POST['applyAs'] ?? '') === 'Player') ? 'selected' : '' ?>>
                                        <?= current_lang() === 'fr' ? 'Joueur' : 'Player' ?>
                                    </option>
                                    <option value="Member" <?= (($_POST['applyAs'] ?? '') === 'Member') ? 'selected' : '' ?>>
                                        <?= current_lang() === 'fr' ? 'Membre' : 'Member' ?>
                                    </option>
                                    <option value="VIP" <?= (($_POST['applyAs'] ?? '') === 'VIP') ? 'selected' : '' ?>>
                                        <?= current_lang() === 'fr' ? 'VIP' : 'VIP' ?>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="split-grid" style="grid-template-columns:1fr 1fr; gap:14px;">
                            <div>
                                <label class="form-label">
                                    <?= current_lang() === 'fr' ? 'Niveau d’expérience' : 'Experience level' ?>
                                </label>
                                <select name="experienceLevel" class="form-control">
                                    <option value=""><?= current_lang() === 'fr' ? 'Choisir' : 'Choose' ?></option>
                                    <option value="Beginner" <?= (($_POST['experienceLevel'] ?? '') === 'Beginner') ? 'selected' : '' ?>>
                                        <?= current_lang() === 'fr' ? 'Débutant' : 'Beginner' ?>
                                    </option>
                                    <option value="Intermediate" <?= (($_POST['experienceLevel'] ?? '') === 'Intermediate') ? 'selected' : '' ?>>
                                        <?= current_lang() === 'fr' ? 'Intermédiaire' : 'Intermediate' ?>
                                    </option>
                                    <option value="Advanced" <?= (($_POST['experienceLevel'] ?? '') === 'Advanced') ? 'selected' : '' ?>>
                                        <?= current_lang() === 'fr' ? 'Avancé' : 'Advanced' ?>
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label">
                                    <?= current_lang() === 'fr' ? 'Discipline préférée' : 'Preferred discipline' ?>
                                </label>
                                <select name="preferredDiscipline" class="form-control">
                                    <option value=""><?= current_lang() === 'fr' ? 'Choisir' : 'Choose' ?></option>
                                    <option value="8-ball" <?= (($_POST['preferredDiscipline'] ?? '') === '8-ball') ? 'selected' : '' ?>>8-ball</option>
                                    <option value="9-ball" <?= (($_POST['preferredDiscipline'] ?? '') === '9-ball') ? 'selected' : '' ?>>9-ball</option>
                                    <option value="10-ball" <?= (($_POST['preferredDiscipline'] ?? '') === '10-ball') ? 'selected' : '' ?>>10-ball</option>
                                    <option value="Snooker" <?= (($_POST['preferredDiscipline'] ?? '') === 'Snooker') ? 'selected' : '' ?>>Snooker</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">
                                <?= current_lang() === 'fr' ? 'Disponibilité (jours/heures)' : 'Availability (days/times)' ?>
                            </label>
                            <input
                                type="text"
                                name="availability"
                                class="form-control"
                                placeholder="<?= current_lang() === 'fr' ? 'Exemple : en semaine après 18:00' : 'Example: weekdays after 18:00' ?>"
                                value="<?= e((string)($_POST['availability'] ?? '')) ?>"
                            >
                        </div>

                        <div>
                            <label class="form-label">
                                <?= current_lang() === 'fr' ? 'Message de motivation' : 'Motivation message' ?>
                            </label>
                            <textarea
                                name="motivationMessage"
                                class="form-control"
                                rows="5"
                                placeholder="<?= current_lang() === 'fr' ? 'Partagez vos objectifs et votre intérêt pour Grand 8' : 'Share your goals and interest in Grand 8' ?>"
                            ><?= e((string)($_POST['motivationMessage'] ?? '')) ?></textarea>
                        </div>

                        <label class="join-consent">
                            <input type="checkbox" name="consentContact" value="1" <?= isset($_POST['consentContact']) ? 'checked' : '' ?>>
                            <span>
                                <?= current_lang() === 'fr'
                                    ? 'J’accepte d’être contacté par Grand 8 concernant ma demande.'
                                    : 'I consent to being contacted by Grand 8 regarding my application.' ?>
                            </span>
                        </label>

                        <div>
                            <button type="submit" class="btn btn-primary join-submit-btn">
                                <?= current_lang() === 'fr' ? 'Envoyer la candidature' : 'Submit application' ?>
                            </button>
                        </div>
                    </form>
                </div>

                <div style="display:grid; gap:20px;">
                    <div class="panel">
                        <h3 style="margin-top:0; margin-bottom:18px;"><?= e(t('join.why.title')) ?></h3>
                        <ul class="membership-list">
                            <?php foreach ($joinWhyItems as $item): ?>
                                <li><?= e((string)$item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="panel join-map-card" style="padding:0; overflow:hidden;">
                        <iframe
                            title="Grand 8 location"
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d564.7104468296337!2d10.137819642300522!3d36.806903781245644!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12fd33c689e671df%3A0x7acc740ec7ab6dfe!2sDynamic%20Billard!5e0!3m2!1sen!2stn!4v1771249435626!5m2!1sen!2stn"
                            width="100%"
                            height="220"
                            style="border:0; display:block;"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>