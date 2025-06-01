<?php
// fetch_sellers.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';

header('Content-Type: application/json');

try {
    // Fetch all sellers (staff) without pagination or filtering
    $sellers = $Ouser->getUsers('', 'id', 'asc', 0, 1000); // Adjust limit as needed

    $data = array();
    foreach ($sellers as $row) {
        $data[] = array(
            "id" => $row['id'],
            "username" => $row['username']
        );
    }

    echo json_encode(['data' => $data]);
} catch (Exception $e) {
    echo json_encode(array("error" => $e->getMessage()));
}