<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$eventsCount = (int)$pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$registrationsCount = (int)$pdo->query("SELECT COUNT(*) FROM event_registrations")->fetchColumn();
$joinsCount = (int)$pdo->query("SELECT COUNT(*) FROM join_requests")->fetchColumn();
$partnersCount = (int)$pdo->query("SELECT COUNT(*) FROM partner_requests")->fetchColumn();
$newsletterCount = (int)$pdo->query("SELECT COUNT(*) FROM newsletter_subscribers")->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>
<div class="topbar">
    <h1 class="page-title">Dashboard</h1>
    <a class="logout-btn" href="/grand8-admin/admin/logout.php">Logout</a>
</div>

<div class="cards">
    <div class="card">
        <div class="card-label">Events</div>
        <p class="card-value"><?= $eventsCount ?></p>
    </div>

    <div class="card">
        <div class="card-label">Registrations</div>
        <p class="card-value"><?= $registrationsCount ?></p>
    </div>

    <div class="card">
        <div class="card-label">Join Requests</div>
        <p class="card-value"><?= $joinsCount ?></p>
    </div>

    <div class="card">
        <div class="card-label">Partner Requests</div>
        <p class="card-value"><?= $partnersCount ?></p>
    </div>

    <div class="card">
        <div class="card-label">Newsletter</div>
        <p class="card-value"><?= $newsletterCount ?></p>
    </div>
</div>

<div class="section">
    <h2>Welcome</h2>
    <p class="muted">
        This is your Grand 8 administration dashboard. From here you will manage events,
        registrations, join requests, partner messages, and newsletter subscriptions.
    </p>
</div>

</main>
</div>
</body>
</html>