<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'DBConn.php';

// Processing Input Loop Actions Handler (Add to Cart logic wrapper)
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $itemId = (int)$_GET['id'];
    
    // Rubric Rule: When adding the same item to Cart, quantity increases instead of duplicating rows
    if (isset($_SESSION['cart'][$itemId])) {
        $_SESSION['cart'][$itemId]++;
    } else {
        $_SESSION['cart'][$itemId] = 1;
    }
    header("Location: clothes.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM tblClothes WHERE stock > 0");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>eShop Catalog - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= getWatermarkClass() ?>">
<?php include 'navbar.php'; ?>

<div class="container" style="margin-top: 40px;">
    <div class="card" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #f8fafc;">
        <div>
            <h3 style="margin:0;">Active Collective Wardrobe</h3>
            <p style="margin:5px 0 0 0; color: var(--text-muted); font-size:0.85rem;">Select premium curated statement items into your current local session tracker array.</p>
        </div>
        <a href="checkout.php" class="btn-primary" style="max-width: 180px; text-align: center;">ShowCart View (<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)</a>
    </div>

    <div class="card">
        <table style="width: 100%;">
            <thead>
                <tr><th>Visual Preview</th><th>Item Title</th><th>Brand</th><th>Available Stock</th><th>Unit Value Price</th><th>Action Allocation</th></tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><div style="width: 50px; height: 50px; background: #e2e8f0; border-radius:4px;"></div></td>
                        <td><strong><?= htmlspecialchars($p['itemName']) ?></strong><br><small><?= htmlspecialchars($p['description']) ?></small></td>
                        <td><?= htmlspecialchars($p['brandName']) ?></td>
                        <td><code><?= $p['stock'] ?> units left</code></td>
                        <td style="font-weight: 700; color: var(--primary-blue);">R<?= number_format($p['price'], 2) ?></td>
                        <td>
                            <a href="clothes.php?action=add&id=<?= $p['id'] ?>" class="btn-outline" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none;">AddtoCart</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>