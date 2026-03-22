<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
?>
<!DOCTYPE html>
<html lang="<?= e(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' . APP_NAME : APP_NAME ?></title>
    <meta name="description" content="<?= isset($pageDescription) ? e($pageDescription) : 'Grand 8 Billiard Club' ?>">
    <link rel="stylesheet" href="<?= e(asset_url('css/style.css')) ?>">
</head>
<body>
<div class="site-shell">