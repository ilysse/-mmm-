<?php 
require '../init.php';

if (isset($_POST['delete_data'])) {
    $delete_id = $_POST['delete_id'];

    // Attempt to delete the customer
    $delete_res = $obj->delete('member', array('id' => $delete_id));
    if ($delete_res) {
        echo "true"; // Customer deleted successfully
    } else {
        echo "Failed to delete customer"; // Deletion failed
    }
}
