<?php
require_once '../init.php';

header('Content-Type: application/json');

// Helper function for logging errors (not output to the user)
function debug_log($message) {
    error_log(print_r($message, true));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;
$username = $_POST['username'] ?? '';
$userRole = $_POST['userRole'] ?? '';
$newPassword = $_POST['password'] ?? '';

// Validate required fields
if ($userId === 0 || empty($username) || empty($userRole)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

$user = new User($pdo);

try {
    // Update username and role
    $user->updateUser($userId, $username, $userRole);

    // Update password if provided
    if (!empty($newPassword)) {
        if ($user->updateUserPassword($userId, $newPassword)) {
            echo json_encode(['success' => true, 'message' => 'User updated successfully, password updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'User updated but password update failed']);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    }
} catch (Exception $e) {
    debug_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
