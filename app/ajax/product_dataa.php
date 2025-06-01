<?php
// product_dataa.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';

header('Content-Type: application/json');

try {
    // Get date range and seller_id from POST
    $issueData = $_POST['issuedate'];
    $sellerId = isset($_POST['seller_id']) ? $_POST['seller_id'] : null;

    // Convert dates
    $data = explode(' - ', $issueData);
    $issu_first_date = $obj->convertDateMysql(trim($data[0]));
    $issu_end_date = $obj->convertDateMysql(trim($data[1]));

    // Validate dates
    if (empty($issu_first_date) || empty($issu_end_date)) {
        echo json_encode(['success' => false, 'message' => 'Invalid date range']);
        exit;
    }

    // Prepare and execute the first query to get invoice IDs
    $query1 = "SELECT id FROM invoice WHERE order_date BETWEEN :issu_first_date AND :issu_end_date";
    if ($sellerId !== null) {
        $query1 .= " AND seller_id = :seller_id";
    }

    $stmt1 = $pdo->prepare($query1);
    $params = [
        ':issu_first_date' => $issu_first_date,
        ':issu_end_date' => $issu_end_date
    ];
    if ($sellerId !== null) {
        $params[':seller_id'] = $sellerId;
    }

    $stmt1->execute($params);
    $invoiceIds = $stmt1->fetchAll(PDO::FETCH_COLUMN);

    if (empty($invoiceIds)) {
        echo json_encode(['success' => true, 'data' => [], 'message' => 'No invoices found for the given date range and seller']);
        exit;
    }

    // Prepare and execute the second query to aggregate quantities and get product names
    $query2 = "
        SELECT p.product_name, SUM(id.quantity) as total_quantity
        FROM invoice_details id
        JOIN products p ON id.pid = p.id
        WHERE id.invoice_no IN (" . implode(',', array_fill(0, count($invoiceIds), '?')) . ")
        GROUP BY id.pid, p.product_name
    ";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute($invoiceIds);

    $aggregatedData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Create the response array
    $response = [
        'success' => true,
        'data' => $aggregatedData
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(array("error" => $e->getMessage()));
}