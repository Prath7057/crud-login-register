<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
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

    $stmt = $pdo->prepare("SELECT image FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit;
    }

    $oldImage = $user['image']; 

    $imageName = $oldImage;
    if ($image && $image['error'] == 0) {
        $imageName = uniqid() . '-' . $image['name'];
        if (move_uploaded_file($image['tmp_name'], 'uploads/' . $imageName)) {
            if (!empty($oldImage) && file_exists('uploads/' . $oldImage)) {
                unlink('uploads/' . $oldImage);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload the new image.']);
            exit;
        }
    }
    $stmt = $pdo->prepare("UPDATE users SET name = ?, gender = ?, mobile = ?, email = ?, image = ? WHERE id = ?");
    $success = $stmt->execute([$name, $gender, $mobile, $email, $imageName, $id]);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
    }
}
?>
