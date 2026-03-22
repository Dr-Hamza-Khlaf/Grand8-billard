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

    $eventId = (int)($_POST['eventId'] ?? 0);
    $fullName = trim((string)($_POST['fullName'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $address = trim((string)($_POST['address'] ?? ''));
    $clubName = trim((string)($_POST['clubName'] ?? ''));
    $level = trim((string)($_POST['level'] ?? ''));
    $notes = trim((string)($_POST['notes'] ?? ''));

    if ($eventId <= 0) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid event.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (
        $fullName === '' ||
        $phone === '' ||
        $email === '' ||
        $address === '' ||
        $clubName === '' ||
        $level === ''
    ) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields.'
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

    $allowedLevels = ['Beginner', 'Intermediate', 'Advanced'];
    if (!in_array($level, $allowedLevels, true)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid level selected.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $eventStmt = $pdo->prepare("
        SELECT id, title, status, is_active
        FROM events
        WHERE id = :id
        LIMIT 1
    ");
    $eventStmt->execute(['id' => $eventId]);
    $event = $eventStmt->fetch();

    if (!$event) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Event not found.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ((int)$event['is_active'] !== 1) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'This event is inactive.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ((string)$event['status'] !== 'Upcoming') {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Registration is closed for this event.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $insertStmt = $pdo->prepare("
        INSERT INTO event_registrations (
            event_id,
            full_name,
            phone,
            email,
            address,
            club_name,
            level,
            notes,
            status
        ) VALUES (
            :event_id,
            :full_name,
            :phone,
            :email,
            :address,
            :club_name,
            :level,
            :notes,
            'new'
        )
    ");

    $insertStmt->execute([
        'event_id' => $eventId,
        'full_name' => $fullName,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'club_name' => $clubName,
        'level' => $level,
        'notes' => $notes !== '' ? $notes : null
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Registration submitted successfully.',
        'registration_id' => (int)$pdo->lastInsertId()
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