<?php
require_once '../init.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$sellerId = $_POST['seller_id'] ?? ''; // Get seller_id from POST request

$searchArray = array();
$searchQuery = "1=1"; // Default query to always return results

// Construct search query
if ($searchValue != '') {
    $searchQuery .= " AND (id LIKE :id OR 
        invoice_number LIKE :invoice_number OR 
        customer_name LIKE :customer_name OR 
        order_date LIKE :order_date OR 
        paid_amount LIKE :paid_amount OR 
        payment_type LIKE :payment_type)";
    
    $searchArray = array(
        'id' => "%$searchValue%",
        'invoice_number' => "%$searchValue%",
        'customer_name' => "%$searchValue%",
        'order_date' => "%$searchValue%",
        'paid_amount' => "%$searchValue%",
        'payment_type' => "%$searchValue%",
    );
}

// Check for seller ID filter
if (!empty($sellerId)) {
    $searchQuery .= " AND seller_id = :seller_id";
    $searchArray['seller_id'] = $sellerId;
}

// Total number of records without filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM invoice WHERE $searchQuery");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM invoice WHERE $searchQuery");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

// Fetch records
$stmt = $pdo->prepare("SELECT * FROM invoice WHERE $searchQuery ORDER BY $columnName $columnSortOrder LIMIT :limit OFFSET :offset");

// Bind values
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll with associative array

$data = array();

foreach ($empRecords as $row) {
    $data[] = array(
        "id" => $row['id'],
        "customer_name" => $row['customer_name'],
        "seller_id" => $row['seller_id'],
        "order_date" => $row['order_date'],
        "sub_total" => $row['sub_total'],
        "prev_due" => $row['pre_cus_due'],
        "net_total" => $row['net_total'],
        "paid_amount" => $row['paid_amount'],
        "due_amount" => $row['due_amount'],
        "return_status" => $row['return_status'],
        "payment_type" => $row['payment_type'],
        "action" => '
            <div class="btns-group">
                <a href="index.php?page=view_sell&&view_id=' . $row["id"] . '" class="btn btn-primary btn-sm rounded-0 " type="button"><i class="fa fa-eye"></i></a>
                <a href="index.php?page=return_sell&&reurn_id=' . $row["id"] . '" class="btn btn-dark btn-sm rounded-0 btn-4" type="button"><i class="fa-solid fa-arrow-rotate-left"></i></a>
                <a href="index.php?page=edit_sell&&edit_id=' . $row["id"] . '"  class="btn btn-secondary btn-sm rounded-0" type="button"><i class="fas fa-edit"></i></a>
                <a href="index.php?page=sell_pay&&id=' . $row['id'] . '" class="dropdown-item" type="button">Pay now</a>
            </div>
        ',
    );
}

// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data,
);

echo json_encode($response);
?>
