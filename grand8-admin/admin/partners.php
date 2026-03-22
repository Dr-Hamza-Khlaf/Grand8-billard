<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];

    if ($deleteId > 0) {
        $stmt = $pdo->prepare("DELETE FROM partner_requests WHERE id = :id");
        $stmt->execute(['id' => $deleteId]);
    }

    header('Location: /grand8-admin/admin/partners.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['partner_id'], $_POST['status'])) {
    $partnerId = (int)$_POST['partner_id'];
    $status = trim((string)($_POST['status'] ?? ''));

    if (
        $partnerId > 0 &&
        in_array($status, ['new', 'reviewed', 'contacted', 'closed'], true)
    ) {
        $stmt = $pdo->prepare("
            UPDATE partner_requests
            SET status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            'status' => $status,
            'id' => $partnerId
        ]);
    }

    header('Location: /grand8-admin/admin/partners.php');
    exit;
}

$stmt = $pdo->query("
    SELECT
        id,
        company_name,
        contact_person,
        email,
        phone,
        partnership_type,
        budget_range,
        message,
        status,
        created_at
    FROM partner_requests
    ORDER BY created_at DESC, id DESC
");
$partners = $stmt->fetchAll();

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

    .badge-contacted {
        color: #cfe5ff;
        background: rgba(59, 130, 246, 0.12);
        border-color: rgba(59, 130, 246, 0.28);
    }

    .badge-closed {
        color: #d7ffd7;
        background: rgba(28, 180, 84, 0.12);
        border-color: rgba(28, 180, 84, 0.28);
    }

    .status-form {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .status-form select {
        min-width: 130px;
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

    .message-box {
        white-space: pre-line;
        line-height: 1.6;
        color: rgba(255,255,255,0.86);
        max-width: 320px;
    }

    .company-name {
        font-weight: 700;
        margin-bottom: 4px;
    }
</style>

<div class="topbar">
    <h1 class="page-title">Partner Requests</h1>
    <a class="logout-btn" href="/grand8-admin/admin/logout.php">Logout</a>
</div>

<div class="section" style="margin-top:0;">
    <h2>Partner and sponsor requests</h2>
    <p class="muted">All sponsor and partnership submissions from the website will appear here.</p>
</div>

<?php if (!$partners): ?>
    <div class="empty-box">
        No partner requests found yet.
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Partnership Type</th>
                    <th>Budget Range</th>
                    <th>Message</th>
                    <th>Created</th>
                    <th>Status / Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partners as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td>
                            <div class="company-name"><?= e($row['company_name']) ?></div>
                        </td>
                        <td><?= e($row['contact_person']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['phone'] ?? '') ?></td>
                        <td><?= e($row['partnership_type'] ?? '') ?></td>
                        <td><?= e($row['budget_range'] ?? '') ?></td>
                        <td>
                            <div class="message-box"><?= e($row['message'] ?? '') ?></div>
                        </td>
                        <td><?= e($row['created_at']) ?></td>
                        <td>
                            <div style="margin-bottom:10px;">
                                <span class="badge badge-<?= e($row['status']) ?>">
                                    <?= e(ucfirst($row['status'])) ?>
                                </span>
                            </div>

                            <form method="post" class="status-form">
                                <input type="hidden" name="partner_id" value="<?= (int)$row['id'] ?>">

                                <select name="status">
                                    <option value="new" <?= $row['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                    <option value="reviewed" <?= $row['status'] === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                    <option value="contacted" <?= $row['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                                    <option value="closed" <?= $row['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                                </select>

                                <button type="submit">Update</button>
                            </form>

                            <a
                                class="delete-link"
                                href="/grand8-admin/admin/partners.php?delete=<?= (int)$row['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this partner request?');"
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