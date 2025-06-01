<?php
// File: app/api/products/list.php
// Purpose: List all products (seller + admin), with pagination + optional search.
// Sellers only see products they added; admins see all.

require_once __DIR__ . '/../../init.php';
header('Content-Type: application/json');

// Ensure session is started
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

// Query parameters
$page       = isset($_GET['page'])    ? max(1, intval($_GET['page'])) : 1;
$perPage    = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 20;
$searchText = isset($_GET['search'])  ? trim($_GET['search'])          : '';
$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Build WHERE clauses
$whereClauses = [];
$params       = [];

// If searching by product name or SKU
if ($searchText !== '') {
    $whereClauses[]        = '(p.product_name LIKE :search OR p.sku LIKE :search)';
    $params[':search']     = "%{$searchText}%";
}

// If filtering by category
if ($categoryId > 0) {
    $whereClauses[]        = 'p.catagory_id = :category_id';
    $params[':category_id'] = $categoryId;
}

// If role is 'seller', only show products they added
if ($_SESSION['user_role'] === 'seller') {
    $whereClauses[]      = 'p.added_by = :added_by';
    $params[':added_by'] = $_SESSION['user_id'];
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

// Count total matching rows
$countSQL  = "
    SELECT COUNT(*) AS total
    FROM products p
    {$whereSQL}
";
$countStmt = $pdo->prepare($countSQL);
foreach ($params as $key => $val) {
    // all filters are strings or ints—bind accordingly
    $countStmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$countStmt->execute();
$totalRow = $countStmt->fetch(PDO::FETCH_OBJ);
$total    = (int)$totalRow->total;

// Calculate pagination
$offset = ($page - 1) * $perPage;

// Fetch actual data; join category for name
$listSQL = "
    SELECT
        p.id,
        p.product_name,
        p.product_id        AS product_code,
        p.brand_name,
        p.catagory_id,
        p.catagory_name,
        p.product_source,
        p.sku,
        p.alert_quanttity   AS alert_quantity,
        p.quantity,
        u.username          AS added_by_username
    FROM products p
    LEFT JOIN user u ON p.added_by = u.id
    {$whereSQL}
    ORDER BY p.product_name ASC, p.id DESC
    LIMIT :offset, :limit
";
$stmt = $pdo->prepare($listSQL);

// Bind filter parameters
foreach ($params as $key => $val) {
    if (is_int($val)) {
        $stmt->bindValue($key, $val, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
}
// Bind pagination
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
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
