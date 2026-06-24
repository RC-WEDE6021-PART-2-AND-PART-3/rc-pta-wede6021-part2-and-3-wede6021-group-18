<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'DBConn.php';

$isLoggedIn = isset($_SESSION['userId']);
$showSellerBanner = true;

if ($isLoggedIn) {
    $stmt = $pdo->prepare("SELECT role FROM tblUser WHERE id = ?");
    $stmt->execute([$_SESSION['userId']]);
    if ($stmt->fetchColumn() === 'Seller') { $showSellerBanner = false; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Pastimes - Premium Pre-Loved Fashion</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .full-width-banner { width: 100%; padding: 90px 40px; border-bottom: 1px solid #1e293b; text-align: center; }
        .banner-wrapper { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
        .category-fluid-grid { display: grid; grid-template-columns: repeat(3, 1fr); width: 100%; border-bottom: 1px solid #1e293b; }
        .category-fluid-cell { height: 260px; display: flex; align-items: center; justify-content: center; text-decoration: none; }
        .category-fluid-cell h3 { color: #ffffff; font-size: 1.3rem; font-weight: 700; text-transform: uppercase; text-shadow: 0 2px 4px rgba(0,0,0,0.4); }
    </style>
</head>
<body class="<?= getWatermarkClass() ?>">
<?php include 'navbar.php'; ?>

<div class="full-width-banner" style="background-color: #000000; color: #ffffff;">
    <div class="banner-wrapper">
        <h2 style="font-size: 2.6rem; font-weight: 800; color: #ffffff; margin-bottom: 15px; letter-spacing: -0.03em;">Pastimes eShop Marketplace</h2>
        <p style="color: #94a3b8; max-width: 650px; line-height: 1.7; margin: 0 auto 30px auto; font-size: 1.05rem;">
            Our core mission is to manage a specialized circular premium streetwear eShop ecosystem. We provide high-contrast interactive catalogs that give exceptional pre-loved garments a second life in your style rotation while lowering global consumption waste.
        </p>
        <a href="clothes.php" class="btn-primary" style="display:inline-block; width:auto; padding:12px 30px; background-color:#ffffff; color:#000000; font-weight:700;">Explore eShop Catalog</a>
    </div>
</div>

<?php if ($showSellerBanner): ?>
<div class="full-width-banner" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('images/sell-bg.jpg') center/cover no-repeat; padding: 110px 40px;">
    <div class="banner-wrapper">
        <a href="register.php" style="text-decoration: none;"><h2 style="font-size: 2.3rem; font-weight: 900; color: #ffffff; line-height: 1.2;">CLEAN OUT YOUR CLOSET. START SELLING NOW &rarr;</h2></a>
    </div>
</div>
<?php endif; ?>

<div class="category-fluid-grid">
    <a href="clothes.php?category=men" class="category-fluid-cell" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.5)), url('images/men-bg.jpg') center/cover no-repeat;"><h3>Shop Men</h3></a>
    <a href="clothes.php?category=women" class="category-fluid-cell" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.5)), url('images/women-bg.jpg') center/cover no-repeat;"><h3>Shop Women</h3></a>
    <a href="clothes.php?category=accessories" class="category-fluid-cell" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.5)), url('images/acc-bg.jpg') center/cover no-repeat;"><h3>Shop Accessories</h3></a>
</div>

<footer style="background-color: #000000; color: #ffffff; padding: 50px 40px; text-align: center;">
    <p style="color: #475569; font-size: 0.85rem;">&copy; <?= date('Y') ?> Pastimes Premium eShop Systems Framework. Verified Local POE Blueprint Standard.</p>
</footer>
</body>
</html>