<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAdmin(): void
{
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /grand8-admin/admin/login.php');
        exit;
    }
}