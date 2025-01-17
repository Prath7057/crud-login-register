<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;

    // Fetch user to delete image
    $stmt = $pdo->prepare("SELECT image FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $image = $user['image'];

        // Delete image file
        if ($image && file_exists('uploads/' . $image)) {
            unlink('uploads/' . $image);
        }

        // Delete user record
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $success = $stmt->execute([$id]);

        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
}
?>
