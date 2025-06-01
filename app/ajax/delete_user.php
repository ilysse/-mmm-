<?php
// Path: C:\xampp\htdocs\ample\app\ajax\delete_user.php

// Include the init file
require_once '../init.php';

// Check if $user is properly initialized
if (!isset($Ouser) || !is_object($Ouser)) {
    echo json_encode(['success' => false, 'message' => 'User object not properly initialized']);
    exit;
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in and is an admin
    

    // Get the user ID from the POST data
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;

    if ($userId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        exit;
    }

    // Call the deleteUser method
    $result = $Ouser->deleteUser($userId);

    // Return the result as JSON
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>