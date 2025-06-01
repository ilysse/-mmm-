<?php
// File: app/api/paymethods/list.php
// Purpose: List all payment methods (seller + admin).

require_once __DIR__ . '/../../init.php';
header('Content-Type: application/json');

// Ensure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authenticated' ]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name FROM paymethode ORDER BY name ASC");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([ 'status' => 'success', 'data' => $rows ]);
} catch (PDOException $e) {
    error_log("List Pay Methods Error: {$e->getMessage()}");
    echo json_encode([ 'status' => 'error', 'message' => 'Failed to fetch pay methods' ]);
}
