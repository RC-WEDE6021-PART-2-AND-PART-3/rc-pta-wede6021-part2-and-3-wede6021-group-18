<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'DBConn.php';

$current = basename($_SERVER['PHP_SELF']);
$showHomeLink = ($current !== 'index.php');

// Determine specific seller system access clearance
$isSeller = false;
if (isset($_SESSION['userId'])) {
    $stmt = $pdo->prepare("SELECT role, status FROM tblUser WHERE id = ?");
    $stmt->execute([$_SESSION['userId']]);
    $u = $stmt->fetch();
    if ($u && $u['role'] === 'Seller' && $u['status'] === 'Approved') {
        $isSeller = true;
    }
}
?>
<nav class="navbar">
    <h1>Pastimes</h1>
    <div class="navbar-links" style="position: relative;">
        
        <?php if (isset($_SESSION['adminId'])): ?>
            <?php if ($current !== 'adminDashboard.php'): ?>
                <a href="adminDashboard.php" style="font-weight: 600;">Home</a>
            <?php endif; ?>
            <a href="adminProfile.php">My Profile</a>
            <a href="logout.php" class="btn-pill" style="background-color: #dc2626;">Logout Console</a>
            
        <?php elseif (isset($_SESSION['userId']) && $isSeller): ?>
            <?php if ($showHomeLink): ?><a href="index.php">Home</a><?php endif; ?>
            <a href="myshop.php" style="color: #10b981; font-weight: 700;">My Shop</a>
            <a href="profile.php">My Profile</a>
            
            <div style="position: relative; display: inline-block; margin-left: 10px; vertical-align: middle;">
                <button onclick="document.getElementById('notifyDrop').classList.toggle('show');" style="background: none; border: none; color: #94a3b8; font-weight: bold; cursor: pointer; font-size: 1.1rem; position: relative; padding: 0;">
                    🔔 <span style="position: absolute; top: -8px; right: -8px; background: var(--primary-blue); color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.65rem;">1</span>
                </button>
                <div id="notifyDrop" class="card" style="display: none; position: absolute; right: 0; top: 30px; width: 320px; z-index: 5000; padding: 15px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: var(--border-clean); text-align: left;">
                    <h5 style="margin-bottom: 10px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600;">Notifications Hub</h5>
                    <div style="font-size: 0.85rem; line-height: 1.4; padding-top: 8px; border-top: 1px solid var(--border-clean); color: var(--text-main);">
                        <strong>Vendor Account Live!</strong> 🎉 Your application has been approved by the administration. You now have complete access rights to list custom garment inventories inside the marketplace.
                    </div>
                </div>
            </div>
            
            <a href="logout.php" class="btn-pill" style="margin-left: 15px;">Logout</a>

        <?php elseif (isset($_SESSION['userId'])): ?>
            <?php if ($showHomeLink): ?><a href="index.php">Home</a><?php endif; ?>
            <a href="clothes.php">Shop Catalog</a>
            <a href="purchases.php">My Purchases</a>
            <a href="checkout.php">Cart (<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)</a>
            <a href="profile.php">My Profile</a>
            
            <div style="position: relative; display: inline-block; margin-left: 10px; vertical-align: middle;">
                <button onclick="document.getElementById('notifyDrop').classList.toggle('show');" style="background: none; border: none; color: #94a3b8; font-weight: bold; cursor: pointer; font-size: 1.1rem; position: relative; padding: 0;">
                    🔔 <span style="position: absolute; top: -8px; right: -8px; background: var(--primary-blue); color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.65rem;">1</span>
                </button>
                <div id="notifyDrop" class="card" style="display: none; position: absolute; right: 0; top: 30px; width: 320px; z-index: 5000; padding: 15px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: var(--border-clean); text-align: left;">
                    <h5 style="margin-bottom: 10px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600;">Notifications Hub</h5>
                    <div style="font-size: 0.85rem; line-height: 1.4; padding-top: 8px; border-top: 1px solid var(--border-clean); color: var(--text-main);">
                        <strong>Welcome to Pastimes!</strong> 🎉 Join our newsletter subscription below to unlock early collections drop alerts before stock runs out.
                    </div>
                </div>
            </div>
            
            <a href="logout.php" class="btn-pill" style="margin-left: 15px;">Logout</a>
            
        <?php else: ?>
            <?php if ($showHomeLink): ?><a href="index.php">Home</a><?php endif; ?>
            <a href="clothes.php">Shop Catalog</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="adminLogin.php" class="btn-pill">Admin Portal</a>
        <?php endif; ?>

    </div>
</nav>

<script>
window.onclick = function(event) {
    if (!event.target.matches('button') && !event.target.closest('#notifyDrop')) {
        var d = document.getElementById('notifyDrop');
        if (d && d.classList.contains('show')) { 
            d.classList.remove('show'); 
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var style = document.createElement('style');
    style.innerHTML = '.show { display: block !important; }';
    document.head.appendChild(style);
});
</script>