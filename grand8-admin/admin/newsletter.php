<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];

    if ($deleteId > 0) {
        $stmt = $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = :id");
        $stmt->execute(['id' => $deleteId]);
    }

    header('Location: /grand8-admin/admin/newsletter.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscriber_id'], $_POST['status'])) {
    $subscriberId = (int)$_POST['subscriber_id'];
    $status = trim((string)($_POST['status'] ?? ''));

    if (
        $subscriberId > 0 &&
        in_array($status, ['active', 'unsubscribed'], true)
    ) {
        $stmt = $pdo->prepare("
            UPDATE newsletter_subscribers
            SET status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            'status' => $status,
            'id' => $subscriberId
        ]);
    }

    header('Location: /grand8-admin/admin/newsletter.php');
    exit;
}

$stmt = $pdo->query("
    SELECT
        id,
        email,
        status,
        created_at
    FROM newsletter_subscribers
    ORDER BY created_at DESC, id DESC
");
$subscribers = $stmt->fetchAll();

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
        min-width: 900px;
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

    .badge-active {
        color: #c7ffd1;
        background: rgba(28, 180, 84, 0.12);
        border-color: rgba(28, 180, 84, 0.28);
    }

    .badge-unsubscribed {
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
        min-width: 140px;
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

    .empty-box {
        background: rgba(8, 8, 12, 0.88);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 22px;
        padding: 24px;
        color: rgba(255,255,255,0.7);
    }
</style>

<div class="topbar">
    <h1 class="page-title">Newsletter</h1>
    <a class="logout-btn" href="/grand8-admin/admin/logout.php">Logout</a>
</div>

<div class="section" style="margin-top:0;">
    <h2>Newsletter subscribers</h2>
    <p class="muted">All subscribed emails from the website will appear here.</p>
</div>

<?php if (!$subscribers): ?>
    <div class="empty-box">
        No newsletter subscribers found yet.
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Status / Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscribers as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['created_at']) ?></td>
                        <td>
                            <div style="margin-bottom:10px;">
                                <span class="badge badge-<?= e($row['status']) ?>">
                                    <?= e(ucfirst($row['status'])) ?>
                                </span>
                            </div>

                            <form method="post" class="status-form">
                                <input type="hidden" name="subscriber_id" value="<?= (int)$row['id'] ?>">

                                <select name="status">
                                    <option value="active" <?= $row['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="unsubscribed" <?= $row['status'] === 'unsubscribed' ? 'selected' : '' ?>>Unsubscribed</option>
                                </select>

                                <button type="submit">Update</button>
                            </form>

                            <a
                                class="delete-link"
                                href="/grand8-admin/admin/newsletter.php?delete=<?= (int)$row['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this subscriber?');"
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