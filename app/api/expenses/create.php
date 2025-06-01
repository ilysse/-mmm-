<?php
// File: app/api/expenses/create.php
// Purpose: Create a new expense (seller + admin). Expects JSON: expense_catagory_id, details, amount, expense_date.

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

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Read JSON payload
$input = json_decode(file_get_contents('php://input'), true);
$catId  = isset($input['expense_catagory_id']) ? intval($input['expense_catagory_id']) : 0;
$details = isset($input['details']) ? trim($input['details']) : '';
$amount  = isset($input['amount']) ? floatval($input['amount']) : 0;
$date    = isset($input['expense_date']) ? trim($input['expense_date']) : '';

// Validate required fields
if ($catId <= 0 || $details === '' || $amount <= 0 || $date === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'All fields (category, details, amount, date) are required and amount must be > 0'
    ]);
    exit;
}

// Optional: verify that expense_catagory_id exists
$checkCatStmt = $pdo->prepare("SELECT id FROM expense_catagory WHERE id = :id LIMIT 1");
$checkCatStmt->bindValue(':id', $catId, PDO::PARAM_INT);
$checkCatStmt->execute();
if ($checkCatStmt->rowCount() === 0) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid expense category ID'
    ]);
    exit;
}

// Insert expense
try {
    $insertSQL = "
        INSERT INTO expense 
        (expense_catagory_id, details, amount, expense_date, added_by)
        VALUES 
        (:catId, :details, :amount, :date, :added_by)
    ";
    $stmt = $pdo->prepare($insertSQL);
    $stmt->bindValue(':catId', $catId, PDO::PARAM_INT);
    $stmt->bindValue(':details', $details, PDO::PARAM_STR);
    $stmt->bindValue(':amount', $amount);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':added_by', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $newId = $pdo->lastInsertId();
    echo json_encode([
        'status'     => 'success',
        'expense_id' => (int)$newId
    ]);
} catch (PDOException $e) {
    error_log("Create Expense Error: " . $e->getMessage());
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to create expense'
    ]);
}
