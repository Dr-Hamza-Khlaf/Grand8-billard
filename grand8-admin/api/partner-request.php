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

    $companyName = trim((string)($_POST['companyName'] ?? ''));
    $contactPerson = trim((string)($_POST['contactPerson'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $partnershipType = trim((string)($_POST['partnershipType'] ?? ''));
    $budgetRange = trim((string)($_POST['budgetRange'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    if ($companyName === '' || $contactPerson === '' || $email === '') {
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

    $stmt = $pdo->prepare("
        INSERT INTO partner_requests (
            company_name,
            contact_person,
            email,
            phone,
            partnership_type,
            budget_range,
            message,
            status
        ) VALUES (
            :company_name,
            :contact_person,
            :email,
            :phone,
            :partnership_type,
            :budget_range,
            :message,
            'new'
        )
    ");

    $stmt->execute([
        'company_name' => $companyName,
        'contact_person' => $contactPerson,
        'email' => $email,
        'phone' => $phone !== '' ? $phone : null,
        'partnership_type' => $partnershipType !== '' ? $partnershipType : null,
        'budget_range' => $budgetRange !== '' ? $budgetRange : null,
        'message' => $message !== '' ? $message : null,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Partner request submitted successfully.',
        'request_id' => (int)$pdo->lastInsertId()
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