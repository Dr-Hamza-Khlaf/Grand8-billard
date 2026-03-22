<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$errors = [];

function makeSlug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text) ?? '';
    return trim($text, '-');
}

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: /grand8-admin/admin/events.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: /grand8-admin/admin/events.php');
    exit;
}

$title = (string)$event['title'];
$slug = (string)$event['slug'];
$eventDate = (string)$event['event_date'];
$location = (string)$event['location'];
$category = (string)$event['category'];
$status = (string)$event['status'];
$description = (string)$event['description'];
$isActive = (int)$event['is_active'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    $eventDate = trim((string)($_POST['event_date'] ?? ''));
    $location = trim((string)($_POST['location'] ?? ''));
    $category = trim((string)($_POST['category'] ?? 'Friendly'));
    $status = trim((string)($_POST['status'] ?? 'Upcoming'));
    $description = trim((string)($_POST['description'] ?? ''));
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if ($slug === '') {
        $slug = makeSlug($title);
    } else {
        $slug = makeSlug($slug);
    }

    if ($slug === '') {
        $errors[] = 'Slug is required.';
    }

    if ($eventDate === '') {
        $errors[] = 'Event date is required.';
    }

    if ($location === '') {
        $errors[] = 'Location is required.';
    }

    if (!in_array($category, ['Tournament', 'Friendly', 'Training'], true)) {
        $errors[] = 'Invalid category selected.';
    }

    if (!in_array($status, ['Upcoming', 'Past'], true)) {
        $errors[] = 'Invalid status selected.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (!$errors) {
        $checkStmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM events
            WHERE slug = :slug
              AND id != :id
        ");
        $checkStmt->execute([
            'slug' => $slug,
            'id' => $id
        ]);
        $slugExists = (int)$checkStmt->fetchColumn() > 0;

        if ($slugExists) {
            $errors[] = 'This slug already exists. Please choose another one.';
        } else {
            $updateStmt = $pdo->prepare("
                UPDATE events
                SET
                    slug = :slug,
                    title = :title,
                    event_date = :event_date,
                    location = :location,
                    category = :category,
                    status = :status,
                    description = :description,
                    is_active = :is_active
                WHERE id = :id
            ");

            $updateStmt->execute([
                'slug' => $slug,
                'title' => $title,
                'event_date' => $eventDate,
                'location' => $location,
                'category' => $category,
                'status' => $status,
                'description' => $description,
                'is_active' => $isActive,
                'id' => $id
            ]);

            header('Location: /grand8-admin/admin/events.php');
            exit;
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<style>
    .form-wrap {
        background: rgba(8, 8, 12, 0.88);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 22px;
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .field.full {
        grid-column: 1 / -1;
    }

    .field label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
    }

    .field input,
    .field select,
    .field textarea {
        width: 100%;
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.10);
        background: #0a0a12;
        color: #fff;
        padding: 14px;
        font-size: 14px;
        outline: none;
    }

    .field textarea {
        min-height: 140px;
        resize: vertical;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        border-color: rgba(226, 27, 27, 0.55);
        box-shadow: 0 0 0 3px rgba(226, 27, 27, 0.12);
    }

    .checkbox-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }

    .checkbox-row input {
        width: auto;
    }

    .actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-radius: 999px;
        font-weight: 700;
    }

    .btn-primary {
        background: #e21b1b;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .btn-secondary {
        border: 1px solid rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.85);
    }

    .alert-error {
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 14px;
        background: rgba(226, 27, 27, 0.12);
        border: 1px solid rgba(226, 27, 27, 0.28);
        color: #ffcccc;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 18px;
    }

    @media (max-width: 800px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="topbar">
    <h1 class="page-title">Edit Event</h1>
    <a class="logout-btn" href="/grand8-admin/admin/logout.php">Logout</a>
</div>

<div class="form-wrap">
    <?php if ($errors): ?>
        <div class="alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="id" value="<?= (int)$id ?>">

        <div class="form-grid">
            <div class="field">
                <label for="title">Title</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= e($title) ?>"
                    required
                >
            </div>

            <div class="field">
                <label for="slug">Slug</label>
                <input
                    type="text"
                    id="slug"
                    name="slug"
                    value="<?= e($slug) ?>"
                    required
                >
            </div>

            <div class="field">
                <label for="event_date">Event Date</label>
                <input
                    type="date"
                    id="event_date"
                    name="event_date"
                    value="<?= e($eventDate) ?>"
                    required
                >
            </div>

            <div class="field">
                <label for="location">Location</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    value="<?= e($location) ?>"
                    required
                >
            </div>

            <div class="field">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="Tournament" <?= $category === 'Tournament' ? 'selected' : '' ?>>Tournament</option>
                    <option value="Friendly" <?= $category === 'Friendly' ? 'selected' : '' ?>>Friendly</option>
                    <option value="Training" <?= $category === 'Training' ? 'selected' : '' ?>>Training</option>
                </select>
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Upcoming" <?= $status === 'Upcoming' ? 'selected' : '' ?>>Upcoming</option>
                    <option value="Past" <?= $status === 'Past' ? 'selected' : '' ?>>Past</option>
                </select>
            </div>

            <div class="field full">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    name="description"
                    required
                ><?= e($description) ?></textarea>
            </div>

            <div class="field full">
                <div class="checkbox-row">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        <?= $isActive === 1 ? 'checked' : '' ?>
                    >
                    <label for="is_active" style="margin:0;">Active event</label>
                </div>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn-primary">Update Event</button>
            <a href="/grand8-admin/admin/events.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</main>
</div>
</body>
</html>