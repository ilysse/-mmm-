<?php
// File: app/api/auth/login.php
// Purpose: Authenticate a user and return JSON with user_id + user_role.

require_once __DIR__ . '/../../init.php';  // Adjust path if needed
header('Content-Type: application/json');

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$password = $input['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode([ 'status' => 'error', 'message' => 'Username and password are required' ]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username LIMIT 1");
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user && password_verify($password, $user->password)) {
        $_SESSION['user_id']   = $user->id;
        $_SESSION['user_role'] = $user->user_role;

        echo json_encode([
            'status'    => 'success',
            'user_id'   => $user->id,
            'user_role' => $user->user_role
        ]);
    } else {
        echo json_encode([ 'status' => 'error', 'message' => 'Invalid username or password' ]);
    }
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    echo json_encode([ 'status' => 'error', 'message' => 'Server error' ]);
}
