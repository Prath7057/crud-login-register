<?php
require 'db.php';
//
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$counter = 1;
?>
<?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo htmlspecialchars($counter++); ?></td>
        <td><?php echo htmlspecialchars($user['name']); ?></td>
        <td><?php echo htmlspecialchars($user['gender']); ?></td>
        <td><?php echo htmlspecialchars($user['mobile']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td>
            <?php if (!empty($user['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['image']); ?>" alt="User Image" style="width: 50px; height: 50px;">
            <?php else: ?>
                No Image
            <?php endif; ?>
        </td>
        <td>
            <button class="btn btn-info btn-sm view-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">View</button>
            <button class="btn btn-warning btn-sm update-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">Update</button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">Delete</button>
        </td>
    </tr>
<?php endforeach; ?>

