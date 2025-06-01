<?php
// File: app/api/expense-categories/delete.php
// Purpose: Delete an expense category (admin-only). Expects "id" query param.

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

// Only DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
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

try {
    $stmt = $pdo->prepare("DELETE FROM expense_catagory WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([ 'status' => 'success' ]);
} catch (PDOException $e) {
    error_log("Delete Expense Category Error: {$e->getMessage()}");
    echo json_encode([ 'status' => 'error', 'message' => 'Failed to delete expense category' ]);
}
