<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];

    if ($deleteId > 0) {
        $stmt = $pdo->prepare("DELETE FROM event_registrations WHERE id = :id");
        $stmt->execute(['id' => $deleteId]);
    }

    header('Location: /grand8-admin/admin/registrations.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registration_id'], $_POST['status'])) {
    $registrationId = (int)$_POST['registration_id'];
    $status = trim((string)($_POST['status'] ?? ''));

    if (
        $registrationId > 0 &&
        in_array($status, ['new', 'reviewed', 'approved', 'rejected'], true)
    ) {
        $stmt = $pdo->prepare("
            UPDATE event_registrations
            SET status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            'status' => $status,
            'id' => $registrationId
        ]);
    }

    header('Location: /grand8-admin/admin/registrations.php');
    exit;
}

$stmt = $pdo->query("
    SELECT
        er.id,
        er.full_name,
        er.phone,
        er.email,
        er.address,
        er.club_name,
        er.level,
        er.notes,
        er.status,
        er.created_at,
        e.title AS event_title,
        e.slug AS event_slug,
        e.event_date
    FROM event_registrations er
    INNER JOIN events e ON e.id = er.event_id
    ORDER BY er.created_at DESC, er.id DESC
");
$registrations = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>
<style>
    .table-wrap {
        overflow-x: auto;
        background: rgba(8, 8, 12, 0.88);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 22px;
        padding: 18px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1300px;
    }

    th, td {
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        vertical-align: top;
    }

    th {
        color: rgba(255,255,255,0.68);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    td {
        color: #fff;
        font-size: 14px;
    }

    .badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid rgba(255,255,255,0.10);
    }

    .badge-new {
        color: #ffd6d6;
        background: rgba(226, 27, 27, 0.12);
        border-color: rgba(226, 27, 27, 0.28);
    }

    .badge-reviewed {
        color: #ffe8b3;
        background: rgba(255, 193, 7, 0.12);
        border-color: rgba(255, 193, 7, 0.28);
    }

    .badge-approved {
        color: #c7ffd1;
        background: rgba(28, 180, 84, 0.12);
        border-color: rgba(28, 180, 84, 0.28);
    }

    .badge-rejected {
        color: #ffcccc;
        background: rgba(255,255,255,0.05);
    }

    .status-form {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .status-form select {
        min-width: 120px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.10);
        background: #0a0a12;
        color: #fff;
        padding: 8px 10px;
    }

    .status-form button {
        border: none;
        border-radius: 10px;
        background: #e21b1b;
        color: #fff;
        padding: 8px 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .delete-link {
        display: inline-block;
        margin-top: 8px;
        color: #ffb3b3;
        font-size: 13px;
    }

    .muted-small {
        color: rgba(255,255,255,0.65);
        font-size: 13px;
        line-height: 1.5;
    }

    .empty-box {
        background: rgba(8, 8, 12, 0.88);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 22px;
        padding: 24px;
        color: rgba(255,255,255,0.7);
    }

    .event-name {
        font-weight: 700;
        margin-bottom: 4px;
    }
</style>

<div class="topbar">
    <h1 class="page-title">Registrations</h1>
    <a class="logout-btn" href="/grand8-admin/admin/logout.php">Logout</a>
</div>

<div class="section" style="margin-top:0;">
    <h2>Event registrations</h2>
    <p class="muted">All submissions from the event registration form will appear here.</p>
</div>

<?php if (!$registrations): ?>
    <div class="empty-box">
        No registrations found yet.
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Club</th>
                    <th>Level</th>
                    <th>Notes</th>
                    <th>Created</th>
                    <th>Status / Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td>
                            <div class="event-name"><?= e($row['event_title']) ?></div>
                            <div class="muted-small">
                                Date: <?= e($row['event_date']) ?><br>
                                Slug: <?= e($row['event_slug']) ?>
                            </div>
                        </td>
                        <td><?= e($row['full_name']) ?></td>
                        <td><?= e($row['phone']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['address'] ?? '') ?></td>
                        <td><?= e($row['club_name'] ?? '') ?></td>
                        <td><?= e($row['level']) ?></td>
                        <td><?= nl2br(e($row['notes'] ?? '')) ?></td>
                        <td><?= e($row['created_at']) ?></td>
                        <td>
                            <div style="margin-bottom:10px;">
                                <span class="badge badge-<?= e($row['status']) ?>">
                                    <?= e(ucfirst($row['status'])) ?>
                                </span>
                            </div>

                            <form method="post" class="status-form">
                                <input type="hidden" name="registration_id" value="<?= (int)$row['id'] ?>">

                                <select name="status">
                                    <option value="new" <?= $row['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                    <option value="reviewed" <?= $row['status'] === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                    <option value="approved" <?= $row['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $row['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>

                                <button type="submit">Update</button>
                            </form>

                            <a
                                class="delete-link"
                                href="/grand8-admin/admin/registrations.php?delete=<?= (int)$row['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this registration?');"
                            >
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

</main>
</div>
</body>
</html>