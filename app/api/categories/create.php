<?php
// File: app/api/categories/create.php
// Purpose: Create a new category (admin-only). Expects JSON: name, description.

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
$description = isset($input['description']) ? trim($input['description']) : '';

if ($name === '') {
    echo json_encode([ 'status' => 'error', 'message' => 'Name is required' ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO catagory (name, description) VALUES (:name, :description)");
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->execute();
    $newId = $pdo->lastInsertId();

    echo json_encode([ 'status' => 'success', 'category_id' => (int)$newId ]);
} catch (PDOException $e) {
    error_log("Create Category Error: {$e->getMessage()}");
    echo json_encode([ 'status' => 'error', 'message' => 'Failed to create category' ]);
}
