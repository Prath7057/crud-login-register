<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $image = $_FILES['image'];

    if ($image['error'] === 0) {
        $imageName = uniqid() . "-" . basename($image['name']);
        $imagePath = 'uploads/' . $imageName;
        move_uploaded_file($image['tmp_name'], $imagePath);

        $stmt = $pdo->prepare("INSERT INTO users (name, gender, mobile, email, image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $gender, $mobile, $email, $imageName])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image upload error']);
    }
}

// read user
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['data' => $users]);

?>
