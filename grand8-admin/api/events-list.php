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
    $status = trim((string)($_GET['status'] ?? ''));
    $category = trim((string)($_GET['category'] ?? ''));
    $activeOnly = (string)($_GET['active'] ?? '1');

    $sql = "
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
        WHERE 1=1
    ";

    $params = [];

    if ($activeOnly === '1') {
        $sql .= " AND is_active = 1";
    }

    if ($status !== '' && in_array($status, ['Upcoming', 'Past'], true)) {
        $sql .= " AND status = :status";
        $params['status'] = $status;
    }

    if ($category !== '' && in_array($category, ['Tournament', 'Friendly', 'Training'], true)) {
        $sql .= " AND category = :category";
        $params['category'] = $category;
    }

    $sql .= " ORDER BY event_date DESC, id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $events = array_map(static function (array $row): array {
        return [
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
    }, $rows);

    echo json_encode([
        'success' => true,
        'count' => count($events),
        'events' => $events
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