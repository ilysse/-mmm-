<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = filter_input(INPUT_POST, 'customer_id', FILTER_SANITIZE_NUMBER_INT);
    $pay_amount = filter_input(INPUT_POST, 'pay_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $pay_type = filter_input(INPUT_POST, 'pay_type', FILTER_SANITIZE_STRING);
    $pay_descrip = filter_input(INPUT_POST, 'pay_descrip', FILTER_SANITIZE_STRING);
    $payment_date = filter_input(INPUT_POST, 'payment_date', FILTER_SANITIZE_STRING); // Added payment_date
    $user = $_SESSION['user_id'];

    if (!empty($payment_date) && !empty($pay_amount)) {
        $pay_inser_query = [
            'customer_id' => $customer_id,
            'payment_date' => $payment_date,
            'payment_amount' => $pay_amount,
            'payment_type' => $pay_type,
            'pay_description' => $pay_descrip,
            'added_by' => $user, // Changed to dynamic user
        ];

        $res = $obj->create('sell_payment', $pay_inser_query);
        if ($res) {
            $sell_info = $obj->find('member', 'id', $customer_id);
            if ($sell_info) {
                $new_paid = $pay_amount + $sell_info->total_paid;
                $new_due = $sell_info->total_due - $pay_amount;

                $update_query = [
                    'total_paid' => $new_paid,
                    'total_due' => $new_due,
                ];

                // Assuming there is a method update in $obj
                $payment_update_res = $obj->update('member', 'id', $customer_id, $update_query);

                echo $payment_update_res ? "Payment successful" : "Something went wrong. Please try again";
            } else {
                echo "Customer not found. Please try again";
            }
        } else {
            echo "Something went wrong. Please try again";
        }
    } else {
        echo "Please fill out all required fields";
    }
} else {
    echo "Invalid request method";
}
?>
