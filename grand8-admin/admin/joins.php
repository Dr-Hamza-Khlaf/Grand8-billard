<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];

    if ($deleteId > 0) {
        $stmt = $pdo->prepare("DELETE FROM join_requests WHERE id = :id");
        $stmt->execute(['id' => $deleteId]);
    }

    header('Location: /grand8-admin/admin/joins.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_id'], $_POST['status'])) {
    $joinId = (int)$_POST['join_id'];
    $status = trim((string)($_POST['status'] ?? ''));

    if (
        $joinId > 0 &&
        in_array($status, ['new', 'reviewed', 'accepted', 'rejected'], true)
    ) {
        $stmt = $pdo->prepare("
            UPDATE join_requests
            SET status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            'status' => $status,
            'id' => $joinId
        ]);
    }

    header('Location: /grand8-admin/admin/joins.php');
    exit;
}

$stmt = $pdo->query("
    SELECT
        id,
        full_name,
        age,
        city,
        phone,
        email,
        apply_as,
        experience_level,
        preferred_discipline,
        availability,
        motivation_message,
        consent_contact,
        status,
        created_at
    FROM join_requests
    ORDER BY created_at DESC, id DESC
");
$joins = $stmt->fetchAll();

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
        min-width: 1500px;
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

    .badge-accepted {
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

    .message-box {
        white-space: pre-line;
        line-height: 1.6;
        color: rgba(255,255,255,0.86);
    }
</style>

<div class="topbar">
    <h1 class="page-title">Join Requests</h1>
    <a class="logout-btn" href="/grand8-admin/admin/logout.php">Logout</a>
</div>

<div class="section" style="margin-top:0;">
    <h2>Club join requests</h2>
    <p class="muted">All membership applications submitted from the website will appear here.</p>
</div>

<?php if (!$joins): ?>
    <div class="empty-box">
        No join requests found yet.
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>City</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Apply As</th>
                    <th>Experience</th>
                    <th>Discipline</th>
                    <th>Availability</th>
                    <th>Motivation</th>
                    <th>Consent</th>
                    <th>Created</th>
                    <th>Status / Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joins as $row): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= e($row['full_name']) ?></td>
                        <td><?= e($row['age'] !== null ? (string)$row['age'] : '') ?></td>
                        <td><?= e($row['city'] ?? '') ?></td>
                        <td><?= e($row['phone']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['apply_as'] ?? '') ?></td>
                        <td><?= e($row['experience_level'] ?? '') ?></td>
                        <td><?= e($row['preferred_discipline'] ?? '') ?></td>
                        <td><?= e($row['availability'] ?? '') ?></td>
                        <td>
                            <div class="message-box"><?= e($row['motivation_message'] ?? '') ?></div>
                        </td>
                        <td><?= (int)$row['consent_contact'] === 1 ? 'Yes' : 'No' ?></td>
                        <td><?= e($row['created_at']) ?></td>
                        <td>
                            <div style="margin-bottom:10px;">
                                <span class="badge badge-<?= e($row['status']) ?>">
                                    <?= e(ucfirst($row['status'])) ?>
                                </span>
                            </div>

                            <form method="post" class="status-form">
                                <input type="hidden" name="join_id" value="<?= (int)$row['id'] ?>">

                                <select name="status">
                                    <option value="new" <?= $row['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                    <option value="reviewed" <?= $row['status'] === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                    <option value="accepted" <?= $row['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                    <option value="rejected" <?= $row['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>

                                <button type="submit">Update</button>
                            </form>

                            <a
                                class="delete-link"
                                href="/grand8-admin/admin/joins.php?delete=<?= (int)$row['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this join request?');"
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