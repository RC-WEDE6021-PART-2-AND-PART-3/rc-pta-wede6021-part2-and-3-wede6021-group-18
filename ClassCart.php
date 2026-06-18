<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'DBConn.php';

// Route back if checkout loop triggers on empty cart configurations
if (empty($_SESSION['cart'])) {
    header("Location: clothes.php"); exit();
}

// Processing User Quantity Adjustment Input Actions Handler
if (isset($_POST['updateQty'])) {
    $id = (int)$_POST['itemId'];
    $newQty = (int)$_POST['newQuantity'];
    if ($newQty > 0) {
        $_SESSION['cart'][$id] = $newQty;
    } else {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: checkout.php"); exit();
}

// Processing Structural Cart Record Item Removal Drop Actions
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int)$_GET['remove']]);
    header("Location: checkout.php"); exit();
}

$cartKeys = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($cartKeys), '?'));
$stmt = $pdo->prepare("SELECT * FROM tblClothes WHERE id IN ($placeholders)");
$stmt->execute($cartKeys);
$cartItems = $stmt->fetchAll();

$grandTotal = 0;

// Execute Checkout Transaction Manifest Sequence
if (isset($_POST['executeCheckout'])) {
    if (!isset($_SESSION['userId'])) {
        header("Location: login.php"); exit();
    }

    $userId = $_SESSION['userId'];
    // Rubric Rules: Generate tracking number parameters using orderNum and tracking sessionId values
    $orderNum = "ORD-" . strtoupper(uniqid());
    $sessionId = session_id();

    foreach ($cartItems as $item) {
        $qty = $_SESSION['cart'][$item['id']];
        $totalCost = $item['price'] * $qty;

        // 1. Write transactional log record lines straight into database tblOrder manifest fields
        $insertOrder = $pdo->prepare("INSERT INTO tblOrder (orderNum, sessionId, userId, itemId, quantity, totalCost, orderDate) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $insertOrder->execute([$orderNum, $sessionId, $userId, $item['id'], $qty, $totalCost]);

        // 2. Decrement physical available store product row item stock level parameters automatically
        $updateStock = $pdo->prepare("UPDATE tblClothes SET stock = stock - ? WHERE id = ?");
        $updateStock->execute([$qty, $item['id']]);
    }

    // 3. Rubric Rule: Empty the local Shopping Cart array session completely post checkout run
    unset($_SESSION['cart']);

    // 4. Rubric Rule: Forward user to complete receipt confirmation workspace summary frame layout
    $_SESSION['last_order_num'] = $orderNum;
    $_SESSION['last_session_id'] = $sessionId;
    header("Location: confirmation.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Review Shopping Cart Contents - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= getWatermarkClass() ?>">
<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 900px; margin-top: 40px;">
    <div class="card">
        <h2 class="card-title">Reviewing Shopping Cart Contents (ShowCart View)</h2>
        
        <table>
            <thead>
                <tr><th>Garment Title</th><th>Unit Cost</th><th>Quantity Selection</th><th>Subtotal Total</th><th>Manage Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): 
                    $sub = $item['price'] * $_SESSION['cart'][$item['id']];
                    $grandTotal += $sub;
                ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($item['itemName']) ?></strong></td>
                        <td>R<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <form method="POST" style="margin:0; display:flex; gap:6px;">
                                <input type="hidden" name="itemId" value="<?= $item['id'] ?>">
                                <input type="number" name="newQuantity" value="<?= $_SESSION['cart'][$item['id']] ?>" min="1" style="width:65px; padding:4px;">
                                <button type="submit" name="updateQty" class="btn-outline" style="padding:4px 10px; font-size:0.75rem;">Modify</button>
                            </form>
                        </td>
                        <td style="font-weight:600;">R<?= number_format($sub, 2) ?></td>
                        <td><a href="checkout.php?remove=<?= $item['id'] ?>" style="color:#dc2626; text-decoration:none; font-size:0.85rem;">Remove Item</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-clean); padding-top: 20px;">
            <h3>Estimated Total Invoice Cost: <span style="color:var(--primary-blue);">R<?= number_format($grandTotal, 2) ?></span></h3>
            <div style="display: flex; gap: 15px;">
                <a href="clothes.php" class="btn-outline" style="text-decoration:none; display:inline-block; padding:12px 20px;">Continue Shopping</a>
                <form method="POST" style="margin:0;">
                    <button type="submit" name="executeCheckout" class="btn-primary" style="padding:12px 24px;">Execute Checkout &rarr;</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>