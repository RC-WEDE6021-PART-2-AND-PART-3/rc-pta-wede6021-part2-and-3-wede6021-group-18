<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'DBConn.php';

if (!isset($_SESSION['userId'])) { header("Location: login.php"); exit(); }
$sellerId = $_SESSION['userId'];

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postItem'])) {
    $name  = trim($_POST['itemName']);
    $brand = trim($_POST['brandName']);
    $desc  = trim($_POST['description']);
    $price = (float)$_POST['price'];
    
    $imgFile = "placeholder.jpg";
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == 0) {
        $imgFile = time() . "_" . basename($_FILES['imageFile']['name']);
        move_uploaded_file($_FILES['imageFile']['tmp_name'], "images/" . $imgFile);
    }

    $stmt = $pdo->prepare("INSERT INTO tblClothes (sellerId, itemName, brandName, description, price, stock, image) VALUES (?, ?, ?, ?, ?, 12, ?)");
    $stmt->execute([$sellerId, $name, $brand, $desc, $price, $imgFile]);
    $message = "Marketplace product listed successfully!";
}

$myItems = $pdo->prepare("SELECT * FROM tblClothes WHERE sellerId = ?");
$myItems->execute([$sellerId]);
$products = $myItems->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>My Shop Window - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= getWatermarkClass() ?>">
<?php include 'navbar.php'; ?>

<div class="container">
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px;">
        <div class="card">
            <h3 class="card-title">Post Clothing Item</h3>
            <?php if(!empty($message)): ?><div class="message success"><?= $message ?></div><?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Garment Title</label><input type="text" name="itemName" required></div>
                <div class="form-group"><label>Brand Heritage Name</label><input type="text" name="brandName" required></div>
                <div class="form-group"><label>Description Summary</label><input type="text" name="description" required></div>
                <div class="form-group"><label>Base Price (ZAR)</label><input type="number" step="0.01" name="price" required></div>
                <div class="form-group"><label>Product Image Showcase</label><input type="file" name="imageFile" required></div>
                <button type="submit" name="postItem" class="btn-primary">List Item Online</button>
            </form>
        </div>
        
        <div class="card">
            <h3 class="card-title">Active Listed Inventory</h3>
            <table>
                <thead><tr><th>Preview</th><th>Garment</th><th>Brand</th><th>Price</th></tr></thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><div style="width:40px; height:40px; background:#e2e8f0;"></div></td>
                            <td><strong><?= htmlspecialchars($p['itemName']) ?></strong></td>
                            <td><?= htmlspecialchars($p['brandName']) ?></td>
                            <td>R<?= number_format($p['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>