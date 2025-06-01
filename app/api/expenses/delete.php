<?php
// File: app/api/expenses/delete.php
// Purpose: Delete an existing expense (seller + admin). Expects "id" query param.

require_once __DIR__ . '/../../init.php';
header('Content-Type: application/json');

// Ensure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Must be logged in
if (empty($_SESSION['user_id'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Not authenticated'
    ]);
    exit;
}

// Only DELETE allowed
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Get expense ID from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid expense ID'
    ]);
    exit;
}

// Fetch existing expense to verify ownership (if seller)
$checkStmt = $pdo->prepare("SELECT * FROM expense WHERE id = :id LIMIT 1");
$checkStmt->bindValue(':id', $id, PDO::PARAM_INT);
$checkStmt->execute();
$existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$existing) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Expense not found'
    ]);
    exit;
}

if ($_SESSION['user_role'] === 'seller' && intval($existing['added_by']) !== $_SESSION['user_id']) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Not authorized to delete this expense'
    ]);
    exit;
}

// Proceed with deletion
try {
    $stmt = $pdo->prepare("DELETE FROM expense WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        'status' => 'success'
    ]);
} catch (PDOException $e) {
    error_log("Delete Expense Error: " . $e->getMessage());
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to delete expense'
    ]);
}
