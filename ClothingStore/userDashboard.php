<?php
// User dashboard after successful login

session_start();

// Check if user is logged in
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['userName'];
?>

<link rel="stylesheet" href="css/style.css">
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="card">
        <h2 class="title">Welcome</h2>

        <p style="text-align:center; margin-bottom:20px;">
            User <strong><?= $userName ?></strong> is successfully logged in.
        </p>

        <a href="clothes.php">
            <button>Browse Clothes</button>
        </a>
    </div>
</div>