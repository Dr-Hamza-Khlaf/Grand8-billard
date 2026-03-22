<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';

$currentPath = $_SERVER['PHP_SELF'] ?? '';

function isActiveNav(string $path, string $currentPath): bool
{
    return str_ends_with($currentPath, $path);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand 8 Admin</title>
    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --bg-1: #130000;
            --bg-2: #230000;
            --bg-3: #120016;
            --panel: rgba(8, 8, 12, 0.88);
            --panel-2: rgba(8, 8, 12, 0.92);
            --border: rgba(255,255,255,0.08);
            --border-soft: rgba(255,255,255,0.06);
            --text: #ffffff;
            --muted: rgba(255,255,255,0.68);
            --primary: #e21b1b;
            --shadow: 0 18px 40px rgba(0,0,0,0.18);
            --radius-xl: 22px;
            --radius-lg: 16px;
            --radius-pill: 999px;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(180, 20, 20, 0.20), transparent 30%),
                linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 38%, var(--bg-3) 100%);
            color: var(--text);
        }

        body {
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .layout {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            min-height: 100vh;
        }

        .sidebar {
            background: var(--panel-2);
            border-right: 1px solid var(--border);
            padding: 24px 18px;
            position: sticky;
            top: 0;
            align-self: start;
            min-height: 100vh;
        }

        .brand {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 24px;
            line-height: 1.1;
        }

        .brand span {
            color: var(--primary);
        }

        .admin-box {
            margin-bottom: 24px;
            padding: 14px;
            border-radius: var(--radius-lg);
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border-soft);
        }

        .admin-box .name {
            font-weight: 700;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .admin-box .role {
            color: var(--muted);
            font-size: 13px;
            word-break: break-word;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav a {
            display: block;
            padding: 12px 14px;
            border-radius: 14px;
            color: rgba(255,255,255,0.82);
            border: 1px solid transparent;
            transition: 0.2s ease;
            font-size: 15px;
        }

        .nav a:hover {
            background: rgba(226, 27, 27, 0.10);
            border-color: rgba(226, 27, 27, 0.25);
            color: #fff;
        }

        .nav a.active {
            background: rgba(226, 27, 27, 0.14);
            border-color: rgba(226, 27, 27, 0.35);
            color: #fff;
            font-weight: 700;
        }

        .main {
            min-width: 0;
            padding: 28px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            word-break: break-word;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border-radius: var(--radius-pill);
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            white-space: nowrap;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 18px;
        }

        .card {
            min-width: 0;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 22px;
            box-shadow: var(--shadow);
        }

        .card-label {
            color: rgba(255,255,255,0.65);
            font-size: 13px;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .card-value {
            font-size: 32px;
            font-weight: 800;
            margin: 0;
            word-break: break-word;
        }

        .section {
            margin-top: 24px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 24px;
            min-width: 0;
        }

        .section h2 {
            margin: 0 0 12px;
            font-size: 22px;
            line-height: 1.2;
            word-break: break-word;
        }

        .muted {
            color: var(--muted);
            line-height: 1.6;
        }

        img,
        table,
        input,
        select,
        textarea,
        button {
            max-width: 100%;
        }

        @media (max-width: 1200px) {
            .cards {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                min-height: auto;
                border-right: 0;
                border-bottom: 1px solid var(--border);
                padding: 18px;
            }

            .brand {
                font-size: 28px;
                margin-bottom: 18px;
            }

            .admin-box {
                margin-bottom: 18px;
            }

            .nav {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .nav a {
                text-align: center;
            }

            .main {
                padding: 20px;
            }

            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .main {
                padding: 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .topbar {
                align-items: stretch;
            }

            .logout-btn {
                width: 100%;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .section,
            .card {
                padding: 18px;
                border-radius: 18px;
            }

            .nav {
                grid-template-columns: 1fr;
            }

            .nav a {
                text-align: left;
            }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">Grand <span>8</span></div>

        <div class="admin-box">
            <div class="name"><?= e($_SESSION['admin_name'] ?? 'Admin') ?></div>
            <div class="role"><?= e($_SESSION['admin_role'] ?? 'admin') ?></div>
        </div>

        <nav class="nav">
            <a
                class="<?= isActiveNav('/dashboard.php', $currentPath) ? 'active' : '' ?>"
                href="/grand8-admin/admin/dashboard.php"
            >
                Dashboard
            </a>
            <a
                class="<?= isActiveNav('/events.php', $currentPath) ? 'active' : '' ?>"
                href="/grand8-admin/admin/events.php"
            >
                Events
            </a>
            <a
                class="<?= isActiveNav('/registrations.php', $currentPath) ? 'active' : '' ?>"
                href="/grand8-admin/admin/registrations.php"
            >
                Registrations
            </a>
            <a
                class="<?= isActiveNav('/joins.php', $currentPath) ? 'active' : '' ?>"
                href="/grand8-admin/admin/joins.php"
            >
                Join Requests
            </a>
            <a
                class="<?= isActiveNav('/partners.php', $currentPath) ? 'active' : '' ?>"
                href="/grand8-admin/admin/partners.php"
            >
                Partner Requests
            </a>
            <a
                class="<?= isActiveNav('/newsletter.php', $currentPath) ? 'active' : '' ?>"
                href="/grand8-admin/admin/newsletter.php"
            >
                Newsletter
            </a>
        </nav>
    </aside>

    <main class="main">