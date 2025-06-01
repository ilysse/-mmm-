<?php 
require '../init.php';

if (isset($_POST['delete_data'])) {
    $delete_id = $_POST['delete_id']; // This would be 103 in your case
    $delete_res = $obj->delete('products', array('id' => $delete_id));
    
    if ($delete_res) {
        echo "true"; // Deletion was successful
    } else {
        echo "false"; // Deletion failed
    }
}
