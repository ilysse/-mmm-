<?php
require_once '../init.php';

if (isset($_POST['customer_name'], $_POST['orderdate'])) {
    try {
        $invoice_number = "S" . time();
        $customer_name = $_POST['customer_name'];
        $find_customer_name = $obj->find('member', 'id', $customer_name);
        $find_customer_name = $find_customer_name->name;
        $orderdate = $obj->convertDateMysql($_POST['orderdate']);

        // Retrieve array values
        $total_quantity = $_POST['total_quantity'];
        $orderQuantity = $_POST['orderQuantity'];
        $price = $_POST['price'];
        $totalPrice = $_POST['totalPrice'];
        $pro_name = $_POST['pro_name'];
        $pid = $_POST['pid'];
        $subtotal = $_POST['subtotal'];
        $discount = $_POST['s_discount_amount'];
        $prev_due = $_POST['prev_due'];
        $netTotal = $_POST['netTotal'];
        $paidBill = $_POST['paidBill'];
        $dueBill = $_POST['dueBill'];
        $payMethode = $_POST['payMethode'];

        // Call the method to store invoice
        $result = $obj->storeCustomerOrderInvoice(
            $invoice_number,
            $customer_name,
            $orderdate,
            $find_customer_name,
            $total_quantity,
            $orderQuantity,
            $price,
            $totalPrice,
            $pro_name,
            $pid,
            $subtotal,
            $discount,
            $prev_due,
            $netTotal,
            $paidBill,
            $dueBill,
            $payMethode
        );

        // Output the result (invoice number or error message)
        echo $result;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
