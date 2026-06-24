<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'DBConn.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM tblAdmin WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && md5($password) === $admin['password']) {
        $_SESSION['adminId'] = $admin['id'];
        $_SESSION['adminName'] = $admin['name'] . " " . $admin['surname'];
        header("Location: adminDashboard.php");
        exit();
    } else {
        $message = "Invalid system administration credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal Access - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #f8fafc; display: flex; flex-direction: column; min-height: 100vh;">

<div class="container" style="max-width: 500px; margin: auto;">
    <div class="card">
        <h2 class="card-title">Admin Control Gateway</h2>

        <?php if (!empty($message)): ?>
            <div class="message error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Admin Username Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group" style="margin-bottom: 25px;">
                <label>Security Password Code</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Verify Access Pass</button>
        </form>
    </div>
</div>

</body>
</html>