<?php
// Admin login page
include 'DBConn.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $adminEmail = $_POST['email'];
    $adminPassword = md5($_POST['password']);

    $sql = "SELECT * FROM tblAdmin 
            WHERE email='$adminEmail' AND password='$adminPassword'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header("Location: adminDashboard.php");
        exit();
    } else {
        $message = "Invalid admin login details";
    }
}
?>

<link rel="stylesheet" href="css/style.css">
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="card">
        <h2 class="title">Admin Login</h2>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter admin email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit">Login</button>
        </form>

        <?php if ($message): ?>
            <div class="message error"><?= $message ?></div>
        <?php endif; ?>
    </div>
</div>