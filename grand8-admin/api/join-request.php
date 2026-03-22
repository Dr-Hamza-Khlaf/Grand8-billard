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

    $fullName = trim((string)($_POST['fullName'] ?? ''));
    $age = trim((string)($_POST['age'] ?? ''));
    $city = trim((string)($_POST['city'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $applyAs = trim((string)($_POST['applyAs'] ?? ''));
    $experienceLevel = trim((string)($_POST['experienceLevel'] ?? ''));
    $preferredDiscipline = trim((string)($_POST['preferredDiscipline'] ?? ''));
    $availability = trim((string)($_POST['availability'] ?? ''));
    $motivationMessage = trim((string)($_POST['motivationMessage'] ?? ''));
    $consentContact = (int)($_POST['consentContact'] ?? 0);

    if ($fullName === '' || $phone === '' || $email === '') {
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

    $ageValue = null;
    if ($age !== '') {
        $ageValue = (int)$age;
        if ($ageValue <= 0) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid age.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO join_requests (
            full_name,
            age,
            city,
            phone,
            email,
            apply_as,
            experience_level,
            preferred_discipline,
            availability,
            motivation_message,
            consent_contact,
            status
        ) VALUES (
            :full_name,
            :age,
            :city,
            :phone,
            :email,
            :apply_as,
            :experience_level,
            :preferred_discipline,
            :availability,
            :motivation_message,
            :consent_contact,
            'new'
        )
    ");

    $stmt->execute([
        'full_name' => $fullName,
        'age' => $ageValue,
        'city' => $city !== '' ? $city : null,
        'phone' => $phone,
        'email' => $email,
        'apply_as' => $applyAs !== '' ? $applyAs : null,
        'experience_level' => $experienceLevel !== '' ? $experienceLevel : null,
        'preferred_discipline' => $preferredDiscipline !== '' ? $preferredDiscipline : null,
        'availability' => $availability !== '' ? $availability : null,
        'motivation_message' => $motivationMessage !== '' ? $motivationMessage : null,
        'consent_contact' => $consentContact === 1 ? 1 : 0,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully.',
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