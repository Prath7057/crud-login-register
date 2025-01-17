<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, gender = ?, mobile = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$name, $gender, $mobile, $email, $id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
    }
}
?>
