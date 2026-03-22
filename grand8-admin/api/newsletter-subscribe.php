<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$allowedOrigins = [
    'http://localhost:3000',
    'http://localhost',
    'http://localhost/grand8',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && in_array($origin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $company = trim((string)($_POST['company'] ?? ''));
    if ($company !== '') {
        echo json_encode([
            'success' => true,
            'message' => 'Submitted.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $email = trim((string)($_POST['email'] ?? ''));

    if ($email === '') {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Please enter your email.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $checkStmt = $pdo->prepare("
        SELECT id, status
        FROM newsletter_subscribers
        WHERE email = :email
        LIMIT 1
    ");
    $checkStmt->execute(['email' => $email]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        if ((string)$existing['status'] === 'active') {
            echo json_encode([
                'success' => true,
                'message' => 'You are already subscribed.',
                'subscriber_id' => (int)$existing['id']
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $updateStmt = $pdo->prepare("
            UPDATE newsletter_subscribers
            SET status = 'active'
            WHERE id = :id
        ");
        $updateStmt->execute(['id' => (int)$existing['id']]);

        echo json_encode([
            'success' => true,
            'message' => 'Subscription reactivated successfully.',
            'subscriber_id' => (int)$existing['id']
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $insertStmt = $pdo->prepare("
        INSERT INTO newsletter_subscribers (
            email,
            status
        ) VALUES (
            :email,
            'active'
        )
    ");
    $insertStmt->execute(['email' => $email]);

    echo json_encode([
        'success' => true,
        'message' => 'Subscribed successfully.',
        'subscriber_id' => (int)$pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}