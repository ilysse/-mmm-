<?php
// File: app/api/users/list.php
// Purpose: List all users (admin-only), with pagination + optional search.

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

// Get query params
$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = isset($_GET['perPage']) ? max(1, intval($_GET['perPage'])) : 20;
$search  = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build WHERE if searching by username
$where    = '';
$params   = [];
if ($search !== '') {
    $where = " WHERE username LIKE :search ";
    $params[':search'] = "%{$search}%";
}

// Count total matching users
$countStmt = $pdo->prepare("SELECT COUNT(*) AS total FROM user {$where}");
foreach ($params as $key => $val) {
    $countStmt->bindValue($key, $val, PDO::PARAM_STR);
}
$countStmt->execute();
$totalRow = $countStmt->fetch(PDO::FETCH_OBJ);
$total     = (int)$totalRow->total;

// Calculate offset
$offset = ($page - 1) * $perPage;

// Fetch user rows (id, username, user_role, created_at)
$sql = "
    SELECT id, username, user_role, created_at
    FROM user
    {$where}
    ORDER BY id DESC
    LIMIT :offset, :limit
";
$stmt = $pdo->prepare($sql);

// Bind search if needed
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON
echo json_encode([
    'status'     => 'success',
    'data'       => $users,
    'pagination' => [
        'page'    => $page,
        'perPage' => $perPage,
        'total'   => $total
    ]
]);
