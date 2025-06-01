<?php
require_once 'app/init.php';

$userId = 4; // Replace with a valid user ID
$newPassword = 'newpassword'; // Replace with a test password

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE user SET password = :password WHERE id = :id");
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Password updated successfully!";
    } else {
        echo "Update failed: " . implode(', ', $stmt->errorInfo());
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}

?>