<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $image = $_FILES['image'] ?? null;

    // Validate inputs
    if (empty($name) || empty($gender) || empty($mobile) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        echo json_encode(['success' => false, 'message' => 'Invalid mobile number.']);
        exit;
    }

    // Handle image upload
    $imageName = '';
    if ($image && $image['error'] == 0) {
        $imageName = uniqid() . '-' . $image['name'];
        move_uploaded_file($image['tmp_name'], 'uploads/' . $imageName);
    }

    // // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (name, gender, mobile, email, image) VALUES (?, ?, ?, ?, ?)");
    $success = $stmt->execute([$name, $gender, $mobile, $email, $imageName]);

    echo json_encode(['success' => $success]);
}
?>
