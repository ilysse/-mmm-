<?php
// File: app/api/expenses/list.php
// Purpose: List all expenses (seller + admin), with pagination + optional search by category or date range.

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

// Get query parameters
$page       = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage    = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 20;
$searchCat  = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$dateFrom   = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$dateTo     = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
$searchText = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build WHERE clauses
$whereClauses = [];
$params       = [];

// If filtering by expense category
if ($searchCat > 0) {
    $whereClauses[]       = 'e.expense_catagory_id = :category_id';
    $params[':category_id'] = $searchCat;
}

// If filtering by date range (expects YYYY-MM-DD format)
if ($dateFrom !== '' && $dateTo !== '') {
    $whereClauses[]         = 'e.expense_date BETWEEN :date_from AND :date_to';
    $params[':date_from']   = $dateFrom;
    $params[':date_to']     = $dateTo;
}

// If searching by text in details
if ($searchText !== '') {
    $whereClauses[]        = 'e.details LIKE :search';
    $params[':search']     = "%{$searchText}%";
}

// Only show expenses added by this seller (if role is 'seller'); admins see all
if ($_SESSION['user_role'] === 'seller') {
    $whereClauses[]        = 'e.added_by = :added_by';
    $params[':added_by']   = $_SESSION['user_id'];
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

// Count total matching rows
$countSQL  = "
    SELECT COUNT(*) AS total
    FROM expense e
    {$whereSQL}
";
$countStmt = $pdo->prepare($countSQL);
foreach ($params as $key => $val) {
    $countStmt->bindValue($key, $val, PDO::PARAM_STR);
}
$countStmt->execute();
$totalRow = $countStmt->fetch(PDO::FETCH_OBJ);
$total    = (int)$totalRow->total;

// Calculate pagination offsets
$offset = ($page - 1) * $perPage;

// Fetch actual data, joining with expense_catagory
$listSQL = "
    SELECT
        e.id,
        e.details,
        e.amount,
        e.expense_date,
        ec.name AS category_name,
        u.username AS added_by_username
    FROM expense e
    LEFT JOIN expense_catagory ec ON e.expense_catagory_id = ec.id
    LEFT JOIN user u ON e.added_by = u.id
    {$whereSQL}
    ORDER BY e.expense_date DESC, e.id DESC
    LIMIT :offset, :limit
";
$stmt = $pdo->prepare($listSQL);
// Bind filter parameters
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val, PDO::PARAM_STR);
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
