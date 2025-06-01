<?php
// File: app/api/auth/logout.php
// Purpose: Destroy current session (logout). Returns JSON.

require_once __DIR__ . '/../../init.php';  // Adjust path if needed
header('Content-Type: application/json');

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    echo json_encode([ 'status' => 'error', 'message' => 'Not authenticated' ]);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([ 'status' => 'error', 'message' => 'Method Not Allowed' ]);
    exit;
}

// Destroy session
$_SESSION = [];
session_destroy();

echo json_encode([ 'status' => 'success' ]);

