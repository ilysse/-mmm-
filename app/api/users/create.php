<?php
// File: app/api/users/create.php
// Purpose: Create a new user (admin-only). Expects JSON body with username, password, user_role.

require_once __DIR__ . '/../../init.php';
header('Content-Type: application/json');

// Ensure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Admin check
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authorized' ]);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Read JSON body
$input = json_decode(file_get_contents('php://input'), true);
$username = isset($input['username']) ? trim($input['username']) : '';
$password = isset($input['password']) ? $input['password'] : '';
$user_role = isset($input['user_role']) ? trim($input['user_role']) : '';

// Validate
if ($username === '' || $password === '' || !in_array($user_role, ['admin', 'seller'], true)) {
    echo json_encode([ 'status' => 'error', 'message' => 'All fields are required and role must be "admin" or "seller"' ]);
    exit;
}

// Check if username already exists
$checkStmt = $pdo->prepare("SELECT id FROM user WHERE username = :username LIMIT 1");
$checkStmt->bindValue(':username', $username, PDO::PARAM_STR);
$checkStmt->execute();
if ($checkStmt->rowCount() > 0) {
    echo json_encode([ 'status' => 'error', 'message' => 'Username already exists' ]);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $insertStmt = $pdo->prepare("
        INSERT INTO user (username, password, user_role, update_by, last_update_at)
        VALUES (:username, :password, :user_role, :update_by, UNIX_TIMESTAMP())
    ");
    $insertStmt->bindValue(':username', $username, PDO::PARAM_STR);
    $insertStmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
    $insertStmt->bindValue(':user_role', $user_role, PDO::PARAM_STR);
    $insertStmt->bindValue(':update_by', $_SESSION['user_id'], PDO::PARAM_INT);
    $insertStmt->execute();

    $newId = $pdo->lastInsertId();
    echo json_encode([ 'status' => 'success', 'user_id' => (int)$newId ]);
} catch (PDOException $e) {
    error_log("Create User Error: " . $e->getMessage());
    echo json_encode([ 'status' => 'error', 'message' => 'Registration failed' ]);
}
