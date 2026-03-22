<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];

    if ($deleteId > 0) {
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
        $stmt->execute(['id' => $deleteId]);
    }

    header('Location: /grand8-admin/admin/events.php');
    exit;
}

$stmt = $pdo->query("
    SELECT id, slug, title, event_date, location, category, status, is_active, created_at
    FROM events
    ORDER BY event_date DESC, id DESC
");
$events = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>
<style>
    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-radius: 999px;
        background: #e21b1b;
        color: #fff;
        font-weight: 700;
    }

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
        min-width: 950px;
    }

    th, td {
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        vertical-align: middle;
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

    .badge-upcoming {
        color: #ffb3b3;
        border-color: rgba(226, 27, 27, 0.35);
        background: rgba(226, 27, 27, 0.10);
    }

    .badge-past {
        color: rgba(255,255,255,0.75);
        background: rgba(255,255,255,0.06);
    }

    .badge-active {
        color: #c7ffd1;
        background: rgba(28, 180, 84, 0.12);
        border-color: rgba(28, 180, 84, 0.28);
    }

    .badge-inactive {
        color: #ffd5d5;
        background: rgba(255,255,255,0.05);
    }

    .row-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .action-link {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.10);
        color: rgba(255,255,255,0.85);
        font-size: 13px;
    }

    .action-link:hover {
        border-color: rgba(226, 27, 27, 0.35);
        color: #fff;
    }

    .delete-link {
        color: #ffb3b3;
    }

    .empty-box {
        background: rgba(8, 8, 12, 0.88);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 22px;
        padding: 24px;
        color: rgba(255,255,255,0.7);
    }
</style>

<div class="topbar">
    <h1 class="page-title">Events</h1>
    <a class="logout-btn" href="/grand8-admin/admin/logout.php">Logout</a>
</div>

<div class="actions-bar">
    <div>
        <h2 style="margin:0 0 6px;">Events management</h2>
        <p class="muted" style="margin:0;">Create, edit, and delete club events.</p>
    </div>

    <a class="add-btn" href="/grand8-admin/admin/event-create.php">+ Add Event</a>
</div>

<?php if (!$events): ?>
    <div class="empty-box">
        No events found yet.
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Active</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= (int)$event['id'] ?></td>
                        <td><?= e($event['title']) ?></td>
                        <td><?= e($event['slug']) ?></td>
                        <td><?= e($event['event_date']) ?></td>
                        <td><?= e($event['location']) ?></td>
                        <td><?= e($event['category']) ?></td>
                        <td>
                            <span class="badge <?= $event['status'] === 'Upcoming' ? 'badge-upcoming' : 'badge-past' ?>">
                                <?= e($event['status']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= (int)$event['is_active'] === 1 ? 'badge-active' : 'badge-inactive' ?>">
                                <?= (int)$event['is_active'] === 1 ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                        <td><?= e($event['created_at']) ?></td>
                        <td>
                            <div class="row-actions">
                                <a class="action-link" href="/grand8-admin/admin/event-edit.php?id=<?= (int)$event['id'] ?>">Edit</a>
                                <a
                                    class="action-link delete-link"
                                    href="/grand8-admin/admin/events.php?delete=<?= (int)$event['id'] ?>"
                                    onclick="return confirm('Are you sure you want to delete this event?');"
                                >
                                    Delete
                                </a>
                            </div>
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