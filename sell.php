<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'DBConn.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];

// Alter scheme safely inline to handle newly introduced user metadata properties if missing
try {
    $pdo->exec("ALTER TABLE tblUser ADD COLUMN surname VARCHAR(100) DEFAULT '' AFTER name;");
    $pdo->exec("ALTER TABLE tblUser ADD COLUMN city VARCHAR(100) DEFAULT 'Polokwane' AFTER email;");
    $pdo->exec("ALTER TABLE tblUser ADD COLUMN avatar VARCHAR(255) DEFAULT 'default-avatar.jpg' AFTER status;");
} catch (Exception $e) { /* Properties already defined inline safely */ }

$message = "";

// 1. Process Metadata Update Changes
if (isset($_POST['updateProfile'])) {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $city = trim($_POST['city']);
    
    $stmt = $pdo->prepare("UPDATE tblUser SET name = ?, surname = ?, city = ? WHERE id = ?");
    $stmt->execute([$name, $surname, $city, $userId]);
    $_SESSION['userName'] = $name;
    $message = "Account information criteria altered cleanly.";
}

// 2. Process Profile Picture Configuration Routing
if (isset($_POST['updateAvatar'])) {
    if (isset($_FILES['avatarFile']) && $_FILES['avatarFile']['error'] == 0) {
        $ext = pathinfo($_FILES['avatarFile']['name'], PATHINFO_EXTENSION);
        $fileName = "user_" . $userId . "_" . time() . "." . $ext;
        $target = "images/" . $fileName;
        
        if (move_uploaded_file($_FILES['avatarFile']['tmp_name'], $target)) {
            $stmt = $pdo->prepare("UPDATE tblUser SET avatar = ? WHERE id = ?");
            $stmt->execute([$fileName, $userId]);
            $message = "Profile picture configurations altered successfully.";
        }
    }
}

// 3. Destructive Action Execution: Drop / Delete Profile Account Row Completely
if (isset($_POST['executeDeletion'])) {
    $stmt = $pdo->prepare("DELETE FROM tblUser WHERE id = ?");
    $stmt->execute([$userId]);
    
    // Annihilate active session variables instantly
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Fetch complete profile attributes
$stmt = $pdo->prepare("SELECT * FROM tblUser WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile Identity - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= getWatermarkClass() ?>">

<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 800px;">
    <div class="card">
        <h2 class="card-title">Account Profile Settings</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div style="display: flex; gap: 40px; flex-wrap: wrap; margin-bottom: 30px; align-items: flex-start;">
            <div style="text-align: center; width: 200px;">
                <div style="width: 140px; height: 140px; border-radius: 50%; background-color: #e2e8f0; margin: 0 auto 15px auto; overflow: hidden; border: 2px solid var(--primary-blue); display: flex; align-items: center; justify-content: center;">
                    <?php if(!empty($user['avatar']) && file_exists('images/'.$user['avatar'])): ?>
                        <img src="images/<?= $user['avatar'] ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="font-size: 3rem; color: #94a3b8;">👤</span>
                    <?php endif; ?>
                </div>
                
                <form method="POST" enctype="multipart/form-data" style="margin: 0;">
                    <label class="btn-outline" style="display: block; padding: 6px 12px; font-size: 0.8rem; cursor: pointer; margin-bottom: 8px;">
                        Select File
                        <input type="file" name="avatarFile" accept="image/*" required style="display: none;" onchange="form.submit();">
                    </label>
                    <input type="hidden" name="updateAvatar" value="1">
                    <span style="font-size: 0.7rem; color: var(--text-muted);">PNG, JPG allocation guidelines</span>
                </form>
            </div>

            <div style="flex: 1; min-width: 300px;">
                <form method="POST" autocomplete="off">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>First Identity Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Surname Extension</label>
                            <input type="text" name="surname" value="<?= htmlspecialchars($user['surname'] ?? '') ?>" placeholder="Enter surname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email Parameter Address (Locked)</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background-color: #f1f5f9; cursor: not-allowed; color: #94a3b8;">
                    </div>
                    <div class="form-group" style="margin-bottom: 30px;">
                        <label>South African Residence City</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($user['city'] ?? 'Polokwane') ?>" required>
                    </div>

                    <button type="submit" name="updateProfile" class="btn-primary" style="max-width: 200px;">Save Profile Criteria</button>
                </form>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-clean); padding-top: 30px; margin-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h4 style="font-size: 0.95rem; font-weight: 600; color: #991b1b; margin-bottom: 4px;">Danger Zone Management</h4>
                <p style="color: var(--text-muted); font-size: 0.8rem; line-height: 1.4; max-width: 450px;">Account deletion is permanent. Your system rows, pending transactions, and invoice history files will be wiped from our servers completely.</p>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <a href="logout.php" onclick="return confirm('Are you sure you want to log out of your profile workspace session?');" class="btn-outline" style="padding: 10px 20px; font-size: 0.85rem;">Logout Session</a>
                
                <form method="POST" style="margin: 0;" onsubmit="return confirm('CRITICAL ACCOUNT DELETION NOTICE:\n\nAre you absolutely sure? Your account will be deleted and this action is not reversible as all your data will be removed from the local database engine permanently.');">
                    <button type="submit" name="executeDeletion" class="btn-primary" style="background-color: #dc2626; padding: 10px 20px; font-size: 0.85rem; font-weight: 500;">Delete Profile Permanently</button>
                </form>
            </div>
        </div>

    </div>
</div>

</body>
</html>