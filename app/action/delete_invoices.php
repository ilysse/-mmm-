<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    require '../init.php';

    if (!isset($_POST['invoice_numbers'])) {
        throw new Exception("No invoice numbers provided");
    }

    $invoice_ids = $_POST['invoice_numbers'];
    $deleted_count = 0;
    $errors = [];

    foreach ($invoice_ids as $invoice_id) {
        $can_delete = true; // Replace with your own logic if needed

        if ($can_delete) {
            $delete_res = $obj->delete('invoice', ['id' => $invoice_id]);
            if ($delete_res) {
                $deleted_count++;
            } else {
                $errors[] = "Failed to delete invoice with ID: $invoice_id";
            }
        } else {
            $errors[] = "No permission to delete invoice with ID: $invoice_id";
        }
    }

    $response = [
        'success' => $deleted_count > 0,
        'message' => $deleted_count > 0 ? "$deleted_count invoice(s) successfully deleted" : "Failed to delete invoices",
        'errors' => $errors
    ];

    echo json_encode($response);

} catch (Exception $e) {
    error_log("Error in delete_invoices.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => "An error occurred: " . $e->getMessage()
    ]);
}
