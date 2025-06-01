<?php
// File: app/api/expense-categories/list.php
// Purpose: List all expense categories (seller + admin), with pagination + optional search.

require_once __DIR__ . '/../../init.php';
header('Content-Type: application/json');

// Ensure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Must be logged in
if (empty($_SESSION['user_id'])) {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authenticated' ]);
    exit;
}

// Query params
$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 20;
$search  = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build WHERE if searching
$where  = '';
$params = [];
if ($search !== '') {
    $where = " WHERE name LIKE :search ";
    $params[':search'] = "%{$search}%";
}

// Count total
$countStmt = $pdo->prepare("SELECT COUNT(*) AS total FROM expense_catagory {$where}");
foreach ($params as $k => $v) {
    $countStmt->bindValue($k, $v, PDO::PARAM_STR);
}
$countStmt->execute();
$totalRow = $countStmt->fetch(PDO::FETCH_OBJ);
$total     = (int)$totalRow->total;

// Fetch data
$offset = ($page - 1) * $perPage;
$sql    = "
    SELECT id, name, description
    FROM expense_catagory
    {$where}
    ORDER BY name ASC
    LIMIT :offset, :limit
";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'status'     => 'success',
    'data'       => $rows,
    'pagination' => [
        'page'    => $page,
        'perPage' => $perPage,
        'total'   => $total
    ]
]);
