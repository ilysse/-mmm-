<?php
require_once '../init.php';

// Set the content type to JSON
header('Content-Type: application/json');

// SQL query to fetch total sales amount grouped by date
$query = "SELECT DATE(order_date) as date, SUM(net_total) as total_sales 
          FROM invoice
          GROUP BY DATE(order_date) 
          ORDER BY DATE(order_date) ASC";

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch the results
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = array();

// Format the response
foreach ($salesData as $row) {
    $response[] = array(
        "date" => $row['date'],
        "total_sales" => $row['total_sales']
    );
}

// Output the data as JSON
echo json_encode($response);
?>
