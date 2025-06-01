<?php
// File: app/api/products/delete.php
// Purpose: Delete an existing product (seller + admin). Expects "id" query param.
// Sellers only delete products they added; admins may delete any.

require_once __DIR__ . '/../../init.php';
header('Content-Type: application/json');

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Must be logged in
if (empty($_SESSION['user_id'])) {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authenticated' ]);
    exit;
}

// Only DELETE allowed
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Get product ID from query string
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($productId <= 0) {
    echo json_encode([ 'status' => 'error', 'message' => 'Invalid product ID' ]);
    exit;
}

// Fetch existing product to verify ownership if seller
$fetchStmt = $pdo->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
$fetchStmt->bindValue(':id', $productId, PDO::PARAM_INT);
$fetchStmt->execute();
$existing = $fetchStmt->fetch(PDO::FETCH_ASSOC);

if (!$existing) {
    echo json_encode([ 'status' => 'error', 'message' => 'Product not found' ]);
    exit;
}

if ($_SESSION['user_role'] === 'seller' && intval($existing['added_by']) !== $_SESSION['user_id']) {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authorized to delete this product' ]);
    exit;
}

// Proceed with deletion
try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindValue(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([ 'status' => 'success' ]);
} catch (PDOException $e) {
    error_log("Delete Product Error: " . $e->getMessage());
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to delete product'
    ]);
}
