<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Grand 8');
define('APP_BASE_URL', '/grand8');
define('DEFAULT_LANG', 'fr');

define('API_BASE_URL', 'http://localhost/grand8-admin/api');
define('SITE_EMAIL', 'contact@grand-8.club');
define('SITE_PHONE', '+216 23 272 590');
define('SITE_ADDRESS', 'Bardo, Tunis, Tunisia');

$supportedLanguages = ['fr', 'en'];

if (isset($_GET['lang']) && in_array($_GET['lang'], $supportedLanguages, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$currentLang = $_SESSION['lang'] ?? DEFAULT_LANG;

if (!in_array($currentLang, $supportedLanguages, true)) {
    $currentLang = DEFAULT_LANG;
}