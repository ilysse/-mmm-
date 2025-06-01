<?php
header('Content-Type: application/json');
require_once '../init.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $user = new User($pdo);
        
        if (!$user->is_admin()) {
            throw new Exception('Unauthorized access');
        }

        $userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($userId) {
            $userData = $user->getUserById($userId);

            if ($userData) {
                echo json_encode(['success' => true, 'data' => $userData]);
            } else {
                throw new Exception('User not found');
            }
        } else {
            throw new Exception('Invalid user ID');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred',
        'errors' => $e->getMessage()
    ]);
}
