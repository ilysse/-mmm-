<?php
// File: app/api/users/update.php
// Purpose: Update an existing user (admin-only). Expects JSON body + "id" query parameter.

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

// Only allow PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Get ID from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode([ 'status' => 'error', 'message' => 'Invalid user ID' ]);
    exit;
}

// Read JSON body
$input = json_decode(file_get_contents('php://input'), true);
$username = isset($input['username']) ? trim($input['username']) : '';
$user_role = isset($input['user_role']) ? trim($input['user_role']) : '';

// Must update at least one of username or role
if ($username === '' && $user_role === '') {
    echo json_encode([ 'status' => 'error', 'message' => 'No fields to update' ]);
    exit;
}

// Build SET clause
$fields = [];
$params = [ ':id' => $id ];

if ($username !== '') {
    $fields[] = "username = :username";
    $params[':username'] = $username;
}
if ($user_role !== '') {
    if (!in_array($user_role, ['admin', 'seller'], true)) {
        echo json_encode([ 'status' => 'error', 'message' => 'Invalid role value' ]);
        exit;
    }
    $fields[] = "user_role = :user_role";
    $params[':user_role'] = $user_role;
}

$sql = "UPDATE user SET " . implode(', ', $fields) . " WHERE id = :id";

try {
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $val) {
        if ($key === ':id') {
            $stmt->bindValue($key, $val, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
    }
    $stmt->execute();

    echo json_encode([ 'status' => 'success' ]);
} catch (PDOException $e) {
    error_log("Update User Error: " . $e->getMessage());
    echo json_encode([ 'status' => 'error', 'message' => 'Update failed' ]);
}
