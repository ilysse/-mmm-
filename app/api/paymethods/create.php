<?php
// File: app/api/paymethods/create.php
// Purpose: Create a new payment method (admin-only). Expects JSON: name.

require_once __DIR__ . '/../../init.php';
header('Content-Type: application/json');

// Ensure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Admin check
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authorized' ]);
    exit;
}

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Read JSON
$input = json_decode(file_get_contents('php://input'), true);
$name = isset($input['name']) ? trim($input['name']) : '';

if ($name === '') {
    echo json_encode([ 'status' => 'error', 'message' => 'Name is required' ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO paymethode (name) VALUES (:name)");
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->execute();
    $newId = $pdo->lastInsertId();

    echo json_encode([ 'status' => 'success', 'paymethod_id' => (int)$newId ]);
} catch (PDOException $e) {
    error_log("Create Pay Method Error: {$e->getMessage()}");
    echo json_encode([ 'status' => 'error', 'message' => 'Failed to create pay method' ]);
}
