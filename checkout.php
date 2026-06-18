<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'DBConn.php';

if (!isset($_SESSION['adminId'])) {
    header("Location: adminLogin.php");
    exit();
}

$adminId = $_SESSION['adminId'];
$message = "";

// 1. Process Core Information Update Forms
if (isset($_POST['updateAdminProfile'])) {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    
    $stmt = $pdo->prepare("UPDATE tblAdmin SET name = ?, surname = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $surname, $email, $adminId]);
    $_SESSION['adminName'] = $name . " " . $surname;
    $message = "Administrative profile settings saved cleanly.";
}

// 2. Process Admin Profile Picture Avatar Configuration Uploads
if (isset($_POST['updateAdminAvatar'])) {
    if (isset($_FILES['avatarFile']) && $_FILES['avatarFile']['error'] == 0) {
        $ext = pathinfo($_FILES['avatarFile']['name'], PATHINFO_EXTENSION);
        $fileName = "admin_" . $adminId . "_" . time() . "." . $ext;
        $target = "images/" . $fileName;
        
        if (move_uploaded_file($_FILES['avatarFile']['tmp_name'], $target)) {
            $stmt = $pdo->prepare("UPDATE tblAdmin SET avatar = ? WHERE id = ?");
            $stmt->execute([$fileName, $adminId]);
            $message = "Administrative profile image configuration saved.";
        }
    }
}

// Fetch raw fresh record properties for Cayden Smith
$stmt = $pdo->prepare("SELECT * FROM tblAdmin WHERE id = ?");
$stmt->execute([$adminId]);
$admin = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Admin Profile Settings - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #f8fafc;">

<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 800px;">
    <div class="card">
        <h2 class="card-title">System Administrator Profile Hub</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div style="display: flex; gap: 40px; flex-wrap: wrap; margin-bottom: 30px; align-items: flex-start;">
            <div style="text-align: center; width: 200px;">
                <div style="width: 140px; height: 140px; border-radius: 50%; background-color: #e2e8f0; margin: 0 auto 15px auto; overflow: hidden; border: 2px solid var(--primary-blue); display: flex; align-items: center; justify-content: center;">
                    <?php if(!empty($admin['avatar']) && file_exists('images/'.$admin['avatar'])): ?>
                        <img src="images/<?= $admin['avatar'] ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="font-size: 3rem; color: #94a3b8;">👮</span>
                    <?php endif; ?>
                </div>
                
                <form method="POST" enctype="multipart/form-data" style="margin: 0;">
                    <label class="btn-outline" style="display: block; padding: 6px 12px; font-size: 0.8rem; cursor: pointer; margin-bottom: 8px;">
                        Upload Photo
                        <input type="file" name="avatarFile" accept="image/*" required style="display: none;" onchange="form.submit();">
                    </label>
                    <input type="hidden" name="updateAdminAvatar" value="1">
                </form>
            </div>

            <div style="flex: 1; min-width: 300px;">
                <form method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Admin System First Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Admin System Surname</label>
                            <input type="text" name="surname" value="<?= htmlspecialchars($admin['surname']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 30px;">
                        <label>Administrative Email Address Access Parameter</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
                    </div>

                    <button type="submit" name="updateAdminProfile" class="btn-primary" style="max-width: 250px;">Save Administrative Criteria</button>
                </form>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-clean); padding-top: 25px; margin-top: 20px; display: flex; justify-content: flex-end;">
            <a href="logout.php" onclick="return confirm('Are you sure you want to log out of your administrative console profile session?');" class="btn-outline" style="padding: 10px 24px; font-size: 0.85rem; font-weight: 500;">Logout Console Session</a>
        </div>

    </div>
</div>

</body>
</html>