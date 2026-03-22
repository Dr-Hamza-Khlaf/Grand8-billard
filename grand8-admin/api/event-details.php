<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);
    exit;
}

try {
    $slug = trim((string)($_GET['slug'] ?? ''));

    if ($slug === '') {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Event slug is required.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT
            id,
            slug,
            title,
            event_date,
            location,
            category,
            status,
            description,
            is_active,
            created_at,
            updated_at
        FROM events
        WHERE slug = :slug
        LIMIT 1
    ");
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Event not found.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $event = [
        'id' => (string)$row['id'],
        'slug' => (string)$row['slug'],
        'title' => (string)$row['title'],
        'date' => date('M j, Y', strtotime((string)$row['event_date'])),
        'event_date' => (string)$row['event_date'],
        'location' => (string)$row['location'],
        'category' => (string)$row['category'],
        'status' => (string)$row['status'],
        'description' => (string)$row['description'],
        'is_active' => (int)$row['is_active'],
        'created_at' => (string)$row['created_at'],
        'updated_at' => (string)$row['updated_at'],
    ];

    echo json_encode([
        'success' => true,
        'event' => $event
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