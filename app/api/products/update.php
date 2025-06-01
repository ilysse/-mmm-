<?php
// File: app/api/products/update.php
// Purpose: Update an existing product (seller + admin). 
// Sellers may only update products they added. Admin may update any.
// Expects JSON + “id” query param.

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

// Only allow PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

// Fetch existing record and verify ownership if seller
$fetchStmt = $pdo->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
$fetchStmt->bindValue(':id', $productId, PDO::PARAM_INT);
$fetchStmt->execute();
$existing = $fetchStmt->fetch(PDO::FETCH_ASSOC);

if (!$existing) {
    echo json_encode([ 'status' => 'error', 'message' => 'Product not found' ]);
    exit;
}

// If current user is a seller, ensure they added this product
if ($_SESSION['user_role'] === 'seller' && intval($existing['added_by']) !== $_SESSION['user_id']) {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authorized to edit this product' ]);
    exit;
}

// Read JSON payload
$input         = json_decode(file_get_contents('php://input'), true);
$productName   = isset($input['product_name'])   ? trim($input['product_name'])   : '';
$brandName     = isset($input['brand_name'])     ? trim($input['brand_name'])     : '';
$categoryId    = isset($input['category_id'])    ? intval($input['category_id'])  : 0;
$productSource = isset($input['product_source']) ? trim($input['product_source']) : '';
$sku           = isset($input['sku'])            ? trim($input['sku'])            : '';
$alertQuantity = isset($input['alert_quantity']) ? intval($input['alert_quantity']): null;
$newQuantity   = isset($input['quantity'])       ? intval($input['quantity'])     : null;

// Build SET clauses dynamically
$fields = [];
$params = [':id' => $productId];

if ($productName !== '') {
    $fields[]              = 'product_name = :product_name';
    $params[':product_name'] = $productName;
}
if ($brandName !== '') {
    $fields[]             = 'brand_name = :brand_name';
    $params[':brand_name'] = $brandName;
}
if ($categoryId > 0) {
    // Verify category exists
    $catStmt = $pdo->prepare("SELECT name FROM catagory WHERE id = :id LIMIT 1");
    $catStmt->bindValue(':id', $categoryId, PDO::PARAM_INT);
    $catStmt->execute();
    $catRow = $catStmt->fetch(PDO::FETCH_OBJ);
    if (!$catRow) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Invalid category_id'
        ]);
        exit;
    }
    $fields[]               = 'catagory_id = :category_id';
    $params[':category_id']   = $categoryId;
    $fields[]               = 'catagory_name = :category_name';
    $params[':category_name'] = $catRow->name;
}
if ($productSource !== '') {
    $fields[]                 = 'product_source = :product_source';
    $params[':product_source'] = $productSource;
}
if ($sku !== '') {
    $fields[]        = 'sku = :sku';
    $params[':sku']   = $sku;
}
if ($alertQuantity !== null) {
    $fields[]               = 'alert_quanttity = :alert_quantity';
    $params[':alert_quantity'] = $alertQuantity;
}
if ($newQuantity !== null) {
    $fields[]              = 'quantity = :quantity';
    $params[':quantity']   = $newQuantity;
}

if (empty($fields)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'No fields provided to update'
    ]);
    exit;
}

$setClause = implode(', ', $fields);
$updateSQL = "UPDATE products SET {$setClause} WHERE id = :id";

try {
    $stmt = $pdo->prepare($updateSQL);
    foreach ($params as $key => $val) {
        if ($key === ':category_id' || $key === ':quantity' || $key === ':alert_quantity' || $key === ':id') {
            $stmt->bindValue($key, intval($val), PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
    }
    $stmt->execute();

    echo json_encode([ 'status' => 'success' ]);
} catch (PDOException $e) {
    error_log("Update Product Error: " . $e->getMessage());
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to update product'
    ]);
}
