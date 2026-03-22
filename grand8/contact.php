<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$pageTitle = current_lang() === 'fr' ? 'Contact' : 'Contact';
$pageDescription = current_lang() === 'fr'
    ? 'Contactez Grand 8 pour sponsors, partenariats et informations.'
    : 'Contact Grand 8 for sponsors, partnerships, and information.';
$currentPage = 'contact';

$translations = load_translations(current_lang());
$contactItems = $translations['contact']['club']['items'] ?? [];

$successMessage = null;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = [
        'company' => trim((string)($_POST['company'] ?? '')),
        'companyName' => trim((string)($_POST['companyName'] ?? '')),
        'contactPerson' => trim((string)($_POST['contactPerson'] ?? '')),
        'email' => trim((string)($_POST['email'] ?? '')),
        'phone' => trim((string)($_POST['phone'] ?? '')),
        'partnershipType' => trim((string)($_POST['partnershipType'] ?? '')),
        'budgetRange' => trim((string)($_POST['budgetRange'] ?? '')),
        'message' => trim((string)($_POST['message'] ?? '')),
    ];

    if ($payload['companyName'] === '' || $payload['contactPerson'] === '' || $payload['email'] === '') {
        $errorMessage = current_lang() === 'fr'
            ? 'Veuillez remplir les champs obligatoires.'
            : 'Please fill in the required fields.';
    } else {
        $apiUrl = 'http://localhost/grand8-admin/api/partner-request.php';

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
            <div class="section-heading" style="margin-bottom: 32px;">
                <p class="eyebrow"><?= e(t('contact.heading.eyebrow')) ?></p>
                <h2><?= e(t('contact.heading.title')) ?></h2>
                <p><?= e(t('contact.heading.description')) ?></p>
            </div>

            <div class="split-grid contact-layout" style="grid-template-columns: 0.92fr 1.08fr; align-items: start; gap: 36px;">
                <div style="display: grid; gap: 24px;">
                    <div class="panel">
                        <h3 style="margin-top:0; margin-bottom:18px;"><?= e(t('contact.club.title')) ?></h3>
                        <ul class="membership-list">
                            <?php foreach ($contactItems as $item): ?>
                                <li><?= e((string)$item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="panel">
                        <h3 style="margin-top:0; margin-bottom:18px;"><?= e(t('contact.highlights.title')) ?></h3>
                        <p class="meta-line" style="margin:0;"><?= e(t('contact.highlights.description')) ?></p>
                    </div>
                </div>

                <div class="panel">
                    <h3 style="margin-top:0;"><?= e(t('contact.form.title')) ?></h3>
                    <p class="meta-line" style="margin-bottom:24px;"><?= e(t('contact.form.description')) ?></p>

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

                    <form method="post" style="display:grid; gap:18px;">
                        <input type="hidden" name="company" value="">

                        <div class="split-grid" style="grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    <?= current_lang() === 'fr' ? 'Entreprise' : 'Company name' ?>
                                </label>
                                <input
                                    type="text"
                                    name="companyName"
                                    class="form-control"
                                    required
                                    value="<?= e((string)($_POST['companyName'] ?? '')) ?>"
                                >
                            </div>

                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    <?= current_lang() === 'fr' ? 'Personne de contact' : 'Contact person' ?>
                                </label>
                                <input
                                    type="text"
                                    name="contactPerson"
                                    class="form-control"
                                    required
                                    value="<?= e((string)($_POST['contactPerson'] ?? '')) ?>"
                                >
                            </div>
                        </div>

                        <div class="split-grid" style="grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">Email</label>
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
                                    <?= current_lang() === 'fr' ? 'Téléphone' : 'Phone' ?>
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="<?= e((string)($_POST['phone'] ?? '')) ?>"
                                >
                            </div>
                        </div>

                        <div class="split-grid" style="grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    <?= current_lang() === 'fr' ? 'Type de partenariat' : 'Partnership type' ?>
                                </label>
                                <select name="partnershipType" class="form-control">
                                    <option value=""><?= current_lang() === 'fr' ? 'Choisir' : 'Choose' ?></option>
                                    <option value="Sponsor" <?= (($_POST['partnershipType'] ?? '') === 'Sponsor') ? 'selected' : '' ?>><?= current_lang() === 'fr' ? 'Sponsor' : 'Sponsor' ?></option>
                                    <option value="Media Partner" <?= (($_POST['partnershipType'] ?? '') === 'Media Partner') ? 'selected' : '' ?>><?= current_lang() === 'fr' ? 'Partenaire média' : 'Media Partner' ?></option>
                                    <option value="Event Partner" <?= (($_POST['partnershipType'] ?? '') === 'Event Partner') ? 'selected' : '' ?>><?= current_lang() === 'fr' ? 'Partenaire événement' : 'Event Partner' ?></option>
                                    <option value="Brand Collaboration" <?= (($_POST['partnershipType'] ?? '') === 'Brand Collaboration') ? 'selected' : '' ?>><?= current_lang() === 'fr' ? 'Collaboration de marque' : 'Brand Collaboration' ?></option>
                                </select>
                            </div>

                            <div>
                                <label class="stat-label" style="display:block; margin-bottom:8px;">
                                    <?= current_lang() === 'fr' ? 'Budget' : 'Budget range' ?>
                                </label>
                                <input
                                    type="text"
                                    name="budgetRange"
                                    class="form-control"
                                    placeholder="<?= current_lang() === 'fr' ? 'ex. 2,000 TND' : 'e.g. 2,000 TND' ?>"
                                    value="<?= e((string)($_POST['budgetRange'] ?? '')) ?>"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="stat-label" style="display:block; margin-bottom:8px;">
                                <?= current_lang() === 'fr' ? 'Message' : 'Message' ?>
                            </label>
                            <textarea name="message" class="form-control" rows="6"><?= e((string)($_POST['message'] ?? '')) ?></textarea>
                        </div>

                        <div style="display:flex; flex-wrap:wrap; gap:12px;">
                            <button type="submit" class="btn btn-primary" style="min-width: 260px;">
                                <?= current_lang() === 'fr' ? 'Envoyer la demande de partenariat' : 'Submit partnership inquiry' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>