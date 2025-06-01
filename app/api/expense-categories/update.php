<?php
// File: app/api/expense-categories/update.php
// Purpose: Update an expense category (admin-only). Expects JSON + "id" query param.

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

// Only PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Get ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode([ 'status' => 'error', 'message' => 'Invalid category ID' ]);
    exit;
}

// Read JSON
$input = json_decode(file_get_contents('php://input'), true);
$name = isset($input['name']) ? trim($input['name']) : '';
$description = isset($input['description']) ? trim($input['description']) : '';

// Must have at least one field
if ($name === '' && $description === '') {
    echo json_encode([ 'status' => 'error', 'message' => 'No fields to update' ]);
    exit;
}

// Build SET clause
$fields = [];
$params = [ ':id' => $id ];

if ($name !== '') {
    $fields[] = "name = :name";
    $params[':name'] = $name;
}
if ($description !== '') {
    $fields[] = "description = :description";
    $params[':description'] = $description;
}

$sql = "UPDATE expense_catagory SET " . implode(', ', $fields) . " WHERE id = :id";
try {
    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }
    $stmt->execute();

    echo json_encode([ 'status' => 'success' ]);
} catch (PDOException $e) {
    error_log("Update Expense Category Error: {$e->getMessage()}");
    echo json_encode([ 'status' => 'error', 'message' => 'Failed to update expense category' ]);
}
