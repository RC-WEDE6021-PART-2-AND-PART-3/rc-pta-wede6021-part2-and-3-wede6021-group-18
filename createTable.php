<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['last_order_num'])) {
    header("Location: index.php"); exit();
}
$orderNum = $_SESSION['last_order_num'];
$sessId = $_SESSION['last_session_id'];

// Clear parameters out cleanly
unset($_SESSION['last_order_num']);
unset($_SESSION['last_session_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Transaction Receipt Success - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color:#f8fafc;">

<div class="container" style="max-width: 650px; margin: 80px auto; text-align: center;">
    <div class="card" style="padding: 50px 40px;">
        <span style="font-size: 4rem;">✅</span>
        <h2 style="margin-top:20px; font-size: 1.8rem; font-weight:700;">Invoice Dispatched Successfully!</h2>
        <p style="color:var(--text-muted); font-size:0.95rem; margin-bottom:30px;">Your purchase data lines have been recorded cleanly into our back-end server ledger systems.</p>
        
        <div style="background: #ffffff; border: 1px solid var(--border-clean); border-radius:6px; padding:20px; margin-bottom:35px; text-align:left; font-size:0.9rem; line-height:1.6;">
            <div>Transaction Reference (orderNum): <strong style="color:var(--primary-blue);"><code><?= htmlspecialchars($orderNum) ?></code></strong></div>
            <div style="word-break: break-all; margin-top:5px;">Session Allocation Tracker (sessionId): <br><code style="color:#0f172a; font-weight:600;"><?= htmlspecialchars($sessId) ?></code></div>
        </div>

        <a href="logout.php" class="btn-primary" style="display:block; text-decoration:none; padding:14px; text-align:center; font-weight:600;">Return to Login / Register Gateway</a>
    </div>
</div>

</body>
</html>