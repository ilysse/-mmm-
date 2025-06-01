<?php
require_once '../init.php';
header('Content-Type: application/json');

// Validate and sanitize the user_id from the query string
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if ($user_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid User ID']);
    exit;
}

// Fetch total sales amount for the seller
$stmt = $pdo->prepare("SELECT SUM(net_total) AS total_sales FROM invoice WHERE seller_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$total_sales = $stmt->fetchColumn();

// Fetch the number of customers the seller dealt with
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT customer_id) AS total_customers FROM invoice WHERE seller_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$total_customers = $stmt->fetchColumn();

// Fetch the top 5 best-selling products for this seller
$stmt = $pdo->prepare(
    "SELECT product_name, SUM(quantity) AS total_quantity 
     FROM invoice_details 
     WHERE invoice_no IN (SELECT id FROM invoice WHERE seller_id = :user_id) 
     GROUP BY product_name 
     ORDER BY total_quantity DESC 
     LIMIT 5"
);
$stmt->execute(['user_id' => $user_id]);
$top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch monthly sales trend (grouped by month)
$stmt = $pdo->prepare(
    "SELECT DATE(order_date) as date, SUM(net_total) as total_sales 
          FROM invoice
          WHERE seller_id = :user_id
          GROUP BY DATE(order_date) 
          ORDER BY DATE(order_date) ASC"
);
$stmt->execute(['user_id' => $user_id]);
$monthly_sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the collected data as JSON
echo json_encode([
    'success' => true,
    'data' => [
        'total_sales' => $total_sales,
        'total_customers' => $total_customers,
        'top_products' => $top_products,
        'monthly_sales' => $monthly_sales
    ]
]);
?>
