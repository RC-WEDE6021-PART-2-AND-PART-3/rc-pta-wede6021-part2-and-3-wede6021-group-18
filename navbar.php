<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'DBConn.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Fetch the user profile by their unique email parameter mapping
    $stmt = $pdo->prepare("SELECT * FROM tblUser WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // 2. Check if your registration handles plain text or MD5 encryption
        if ($password === $user['password'] || md5($password) === $user['password']) {
            
            // Set session global variables cleanly
            $_SESSION['userId'] = $user['id'];
            $_SESSION['userName'] = $user['name'];
            $_SESSION['userRole'] = $user['role'];
            
            // Forward securely to the storefront homepage index
            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid security password reference key mapping.";
        }
    } else {
        $message = "No customer account matches that identity.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #f8fafc; display: flex; flex-direction: column; min-height: 100vh;">

<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 500px; margin: auto; padding-top: 40px; padding-bottom: 40px;">
    <div class="card" style="padding: 40px;">
        <h2 class="card-title" style="font-size: 1.6rem; font-weight: 700; margin-bottom: 20px; letter-spacing: -0.02em;">Login</h2>

        <?php if (!empty($message)): ?>
            <div class="message error" style="margin-bottom: 20px; padding: 12px; background-color: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; border-radius: var(--radius-premium); font-size: 0.9rem; font-weight: 500;">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="form-group">
                <label style="font-weight: 600; font-size: 0.85rem; color: #334155;">Account Email</label>
                <input type="email" name="email" placeholder="name@domain.com" required style="width: 100%; padding: 12px; border: 1px solid var(--border-clean); border-radius: var(--radius-premium); margin-top: 6px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 30px;">
                <label style="font-weight: 600; font-size: 0.85rem; color: #334155;">Password Reference Key</label>
                <input type="password" name="password" placeholder="••••••••" required style="width: 100%; padding: 12px; border: 1px solid var(--border-clean); border-radius: var(--radius-premium); margin-top: 6px;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-weight: 600;">Login to Store</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; font-size: 0.85rem; color: var(--text-muted);">
            Don't have an account expression profile? <a href="register.php" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">Register Now</a>
        </p>
    </div>
</div>

</body>
</html>