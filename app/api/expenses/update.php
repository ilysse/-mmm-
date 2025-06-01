<?php
// File: app/api/expenses/update.php
// Purpose: Update an existing expense (seller + admin). Expects JSON + "id" query param.

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

// Only PUT allowed
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

// Fetch existing expense to verify ownership (if seller) or existence
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

// If role is 'seller', ensure they own this expense
if ($_SESSION['user_role'] === 'seller' && intval($existing['added_by']) !== $_SESSION['user_id']) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Not authorized to edit this expense'
    ]);
    exit;
}

// Read JSON payload
$input   = json_decode(file_get_contents('php://input'), true);
$catId   = isset($input['expense_catagory_id']) ? intval($input['expense_catagory_id']) : 0;
$details = isset($input['details']) ? trim($input['details']) : '';
$amount  = isset($input['amount']) ? floatval($input['amount']) : 0;
$date    = isset($input['expense_date']) ? trim($input['expense_date']) : '';

// Build SET clause dynamically (only update supplied fields)
$fields = [];
$params = [':id' => $id];

if ($catId > 0) {
    // Verify category exists
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
    $fields[]        = "expense_catagory_id = :expense_catagory_id";
    $params[':expense_catagory_id'] = $catId;
}

if ($details !== '') {
    $fields[]      = "details = :details";
    $params[':details'] = $details;
}

if ($amount > 0) {
    $fields[]      = "amount = :amount";
    $params[':amount'] = $amount;
}

if ($date !== '') {
    $fields[]    = "expense_date = :expense_date";
    $params[':expense_date'] = $date;
}

if (empty($fields)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'No fields provided to update'
    ]);
    exit;
}

$setClause = implode(', ', $fields);
$updateSQL = "UPDATE expense SET {$setClause} WHERE id = :id";

try {
    $stmt = $pdo->prepare($updateSQL);
    foreach ($params as $key => $val) {
        if ($key === ':id' || $key === ':expense_catagory_id') {
            $stmt->bindValue($key, $val, PDO::PARAM_INT);
        } elseif ($key === ':amount') {
            $stmt->bindValue($key, $val);
        } else {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
    }
    $stmt->execute();

    echo json_encode([
        'status' => 'success'
    ]);
} catch (PDOException $e) {
    error_log("Update Expense Error: " . $e->getMessage());
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to update expense'
    ]);
}
