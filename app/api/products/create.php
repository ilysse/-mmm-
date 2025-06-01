<?php
// File: app/api/products/create.php
// Purpose: Create a new product (seller + admin). Expects JSON.
// Sellers cannot set any “price”—only product metadata.

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

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Read JSON payload
$input = json_decode(file_get_contents('php://input'), true);

$productName   = isset($input['product_name'])   ? trim($input['product_name'])   : '';
$brandName     = isset($input['brand_name'])     ? trim($input['brand_name'])     : '';
$categoryId    = isset($input['category_id'])    ? intval($input['category_id'])  : 0;
$productSource = isset($input['product_source']) ? trim($input['product_source']) : '';
$sku           = isset($input['sku'])            ? trim($input['sku'])            : '';
$alertQuantity = isset($input['alert_quantity']) ? intval($input['alert_quantity']): 0;

// Validate required fields
if ($productName === '' || $brandName === '' || $categoryId <= 0 || $productSource === '' || $sku === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'product_name, brand_name, category_id, product_source, and sku are required'
    ]);
    exit;
}

// Look up category name from catagory table
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
$categoryName = $catRow->name;

// Generate a unique product_code (matching web logic “P” . time())
$productCode = 'P' . time();

// Default quantity is 0 when creating
$quantity = 0;

// Insert into products
try {
    $insertSQL = "
        INSERT INTO products 
        (product_name, product_id, brand_name, catagory_id, catagory_name, product_source, sku, alert_quanttity, quantity, added_by)
        VALUES 
        (:product_name, :product_code, :brand_name, :category_id, :category_name, :product_source, :sku, :alert_quantity, :quantity, :added_by)
    ";
    $stmt = $pdo->prepare($insertSQL);
    $stmt->bindValue(':product_name',  $productName,    PDO::PARAM_STR);
    $stmt->bindValue(':product_code',  $productCode,    PDO::PARAM_STR);
    $stmt->bindValue(':brand_name',    $brandName,      PDO::PARAM_STR);
    $stmt->bindValue(':category_id',   $categoryId,     PDO::PARAM_INT);
    $stmt->bindValue(':category_name', $categoryName,   PDO::PARAM_STR);
    $stmt->bindValue(':product_source',$productSource,  PDO::PARAM_STR);
    $stmt->bindValue(':sku',           $sku,            PDO::PARAM_STR);
    $stmt->bindValue(':alert_quantity',$alertQuantity,  PDO::PARAM_INT);
    $stmt->bindValue(':quantity',      $quantity,       PDO::PARAM_INT);
    $stmt->bindValue(':added_by',      $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $newId = $pdo->lastInsertId();
    echo json_encode([
        'status'      => 'success',
        'product_id'  => (int)$newId
    ]);
} catch (PDOException $e) {
    error_log("Create Product Error: " . $e->getMessage());
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to create product'
    ]);
}
