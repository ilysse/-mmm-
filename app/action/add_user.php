<?php
require_once '../init.php';

// Function to validate CSRF token
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
}

if (isset($_POST['username'], $_POST['password'], $_POST['confirmPassword'], $_POST['csrf_token'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $csrf_token = $_POST['csrf_token'];

    // Debugging output
    error_log("Received CSRF Token: " . $csrf_token);
    error_log("Session CSRF Token: " . $_SESSION['csrf_token']);

    // Validate CSRF token
    if (!validate_csrf_token($csrf_token)) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
        exit;
    }

    // Basic server-side validation
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long.']);
        exit;
    }

    // Register the user
    $result = $Ouser->register($username, $password, $_POST['user_role']);

    // Return the result as JSON
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
