<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'DBConn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= getWatermarkClass() ?>">

<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 800px;">
    <div class="card">
        <h2 class="card-title" style="text-align: left; font-size: 1.8rem; margin-bottom: 10px;">Our Story</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 25px;">Established with a strict focus on sustainable apparel curation loops.</p>
        
        <div style="font-size: 0.95rem; line-height: 1.7; display: flex; flex-direction: column; gap: 15px; color: var(--text-main);">
            <p>Pastimes was born out of a simple realization: the most exceptional garments already exist. Fast fashion systems overwhelm closets with low-quality iterations, while magnificent vintage textiles and high-end streetwear garments sit dormant in storage units.</p>
            <p>We built this infrastructure to serve as an uncompromising circular gateway. Every piece made available inside our store undergoes careful physical inspection, measurement evaluation, and material authentication processing to make sure you receive pristine items.</p>
            <p>By giving garments a second lifecycle, we minimize textile manufacturing waste footprint variables while maintaining a sharp collection profile for local collectors.</p>
        </div>
        
        <div style="margin-top: 35px; padding-top: 20px; border-top: 1px solid var(--border-clean);">
            <a href="clothes.php" class="btn-primary" style="max-width: 220px;">Explore Collections</a>
        </div>
    </div>
</div>

</body>
</html>