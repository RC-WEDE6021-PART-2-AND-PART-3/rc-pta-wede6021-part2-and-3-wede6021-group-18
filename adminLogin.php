<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'DBConn.php';

if (!isset($_SESSION['adminId'])) {
    header("Location: adminLogin.php"); exit();
}

// --- POE RUBRIC COMPLIANT PRODUCT MANIPULATION CONTROLS ---

// 1. Action: ADD / INSERT New Item Listing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertProduct'])) {
    $name = trim($_POST['itemName']);
    $brand = trim($_POST['brandName']);
    $desc = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    
    $stmt = $pdo->prepare("INSERT INTO tblClothes (itemName, brandName, description, price, stock, image) VALUES (?, ?, ?, ?, ?, 'about-bg.jpg')");
    $stmt->execute([$name, $brand, $desc, $price, $stock]);
    header("Location: adminDashboard.php"); exit();
}

// 2. Action: EDIT Existing Item Listing Parameters
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editProduct'])) {
    $pid = (int)$_POST['productId'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    
    $stmt = $pdo->prepare("UPDATE tblClothes SET price = ?, stock = ? WHERE id = ?");
    $stmt->execute([$price, $stock, $pid]);
    header("Location: adminDashboard.php"); exit();
}

// 3. Action: DELETE Item Listing
if (isset($_GET['deleteProduct'])) {
    $pdo->prepare("DELETE FROM tblClothes WHERE id = ?")->execute([(int)$_GET['deleteProduct']]);
    header("Location: adminDashboard.php"); exit();
}

// --- USER ACCEPTS/REJECTS REGISTRATION PROCESSING LINKS ---
if (isset($_GET['approveUser'])) {
    $pdo->prepare("UPDATE tblUser SET role = 'Seller', status = 'Approved' WHERE id = ?")->execute([(int)$_GET['approveUser']]);
    header("Location: adminDashboard.php"); exit();
}
if (isset($_GET['rejectUser'])) {
    $pdo->prepare("UPDATE tblUser SET status = 'Pending', role = 'Buyer' WHERE id = ?")->execute([(int)$_GET['rejectUser']]);
    header("Location: adminDashboard.php"); exit();
}
if (isset($_GET['deleteUser'])) {
    $pdo->prepare("DELETE FROM tblUser WHERE id = ?")->execute([(int)$_GET['deleteUser']]);
    header("Location: adminDashboard.php"); exit();
}

// Fetch complete, separated, up-to-date relational datasets
$pendingQueue = $pdo->query("SELECT * FROM tblUser WHERE status = 'Pending' ORDER BY id DESC")->fetchAll();
$activeBuyers = $pdo->query("SELECT * FROM tblUser WHERE role = 'Buyer' AND status = 'Approved' ORDER BY id DESC")->fetchAll();
$activeSellers = $pdo->query("SELECT * FROM tblUser WHERE role = 'Seller' AND status = 'Approved' ORDER BY id DESC")->fetchAll();
$allStocks = $pdo->query("SELECT * FROM tblClothes ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Master Control Workspace Panel - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function switchTab(event, sectionId) {
            var idx, contents, buttons;
            contents = document.getElementsByClassName("dashboard-tab-content");
            for (idx = 0; idx < contents.length; idx++) { contents[idx].style.display = "none"; }
            buttons = document.getElementsByClassName("tab-btn");
            for (idx = 0; idx < buttons.length; idx++) { buttons[idx].className = buttons[idx].className.replace(" active", ""); }
            document.getElementById(sectionId).style.display = "block";
            event.currentTarget.className += " active";
        }
    </script>
</head>
<body style="background-color: #f8fafc;">
<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 1300px; margin: 40px auto; width: 95%;">
    <div class="card" style="padding: 40px 50px;">
        <h2 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 5px;">Master Control Workspace Panel</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 30px;">Primary Administrative Gateway Profile.</p>
        
        <div class="tabs-nav" style="margin-bottom: 30px;">
            <button class="tab-btn active" onclick="switchTab(event, 'queue-tab')">Pending Requests (<?= count($pendingQueue) ?>)</button>
            <button class="tab-btn" onclick="switchTab(event, 'buyers-tab')">Active Buyers (<?= count($activeBuyers) ?>)</button>
            <button class="tab-btn" onclick="switchTab(event, 'sellers-tab')">Active Sellers (<?= count($activeSellers) ?>)</button>
            <button class="tab-btn" onclick="switchTab(event, 'products-tab')">Product Inventory Management (<?= count($allStocks) ?>)</button>
        </div>

        <div id="queue-tab" class="dashboard-tab-content" style="display: block;">
            <table style="width: 100%;">
                <thead><tr><th>ID</th><th>Identity Name</th><th>Email Address</th><th>Intended Role</th><th>Status</th><th>Actions Processing</th></tr></thead>
                <tbody>
                    <?php foreach ($pendingQueue as $u): ?>
                        <tr>
                            <td><code>#<?= $u['id'] ?></code></td>
                            <td><strong><?= htmlspecialchars($u['name'] . ' ' . $u['surname']) ?></strong></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><span style="color: var(--primary-blue); font-weight:600;"><?= $u['role'] ?></span></td>
                            <td><span class="status-badge status-pending"><?= $u['status'] ?></span></td>
                            <td>
                                <a href="adminDashboard.php?approveUser=<?= $u['id'] ?>" style="color: #16a34a; font-weight:600; text-decoration:none; margin-right:15px;">Accept</a>
                                <a href="adminDashboard.php?rejectUser=<?= $u['id'] ?>" style="color: #d97706; font-weight:600; text-decoration:none; margin-right:15px;">Reject</a>
                                <a href="adminDashboard.php?deleteUser=<?= $u['id'] ?>" style="color: #dc2626; text-decoration:none;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="buyers-tab" class="dashboard-tab-content" style="display: none;">
            <table style="width: 100%;">
                <thead><tr><th>ID</th><th>Identity Name</th><th>Email Address</th><th>City</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($activeBuyers as $u): ?>
                        <tr><td><code>#<?= $u['id'] ?></code></td><td><strong><?= htmlspecialchars($u['name'] . ' ' . $u['surname']) ?></strong></td><td><?= htmlspecialchars($u['email']) ?></td><td><?= htmlspecialchars($u['city']) ?></td><td><a href="adminDashboard.php?deleteUser=<?= $u['id'] ?>" style="color:#dc2626; text-decoration:none;">Terminate</a></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="sellers-tab" class="dashboard-tab-content" style="display: none;">
            <table style="width: 100%;">
                <thead><tr><th>ID</th><th>Boutique Trading Name</th><th>Owner</th><th>Email Address</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($activeSellers as $u): ?>
                        <tr><td><code>#<?= $u['id'] ?></code></td><td style="color:#10b981; font-weight:700;"><?= htmlspecialchars($u['shopName'] ?? 'Independent Store') ?></td><td><strong><?= htmlspecialchars($u['name'] . ' ' . $u['surname']) ?></strong></td><td><?= htmlspecialchars($u['email']) ?></td><td><a href="adminDashboard.php?deleteUser=<?= $u['id'] ?>" style="color:#dc2626; text-decoration:none;">Terminate</a></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="products-tab" class="dashboard-tab-content" style="display: none;">
            
            <div style="background:#f8fafc; border:1px solid var(--border-clean); padding:20px; border-radius:8px; margin-bottom:30px;">
                <h4 style="margin-top:0;">Insert New Stock Item Listing</h4>
                <form method="POST" style="display:grid; grid-template-columns: repeat(5, 1fr) auto; gap:12px; align-items:end;">
                    <div><label style="font-size:0.75rem; font-weight:600;">Title</label><input type="text" name="itemName" required style="padding:8px;"></div>
                    <div><label style="font-size:0.75rem; font-weight:600;">Brand</label><input type="text" name="brandName" required style="padding:8px;"></div>
                    <div><label style="font-size:0.75rem; font-weight:600;">Description</label><input type="text" name="description" required style="padding:8px;"></div>
                    <div><label style="font-size:0.75rem; font-weight:600;">Price (ZAR)</label><input type="number" step="0.01" name="price" required style="padding:8px;"></div>
                    <div><label style="font-size:0.75rem; font-weight:600;">Stock Qty</label><input type="number" name="stock" required style="padding:8px;"></div>
                    <button type="submit" name="insertProduct" class="btn-primary" style="padding:10px 15px; font-size:0.85rem;">Add/Insert Item</button>
                </form>
            </div>

            <table style="width: 100%;">
                <thead><tr><th>ID</th><th>Garment Specifications</th><th>Current Price</th><th>Stock Level</th><th>Modify Criteria (EDIT)</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($allStocks as $s): ?>
                        <tr>
                            <td><code>#<?= $s['id'] ?></code></td>
                            <td><strong><?= htmlspecialchars($s['itemName']) ?></strong> — <small><?= htmlspecialchars($s['brandName']) ?></small></td>
                            <td>R<?= number_format($s['price'], 2) ?></td>
                            <td><code><?= $s['stock'] ?> units</code></td>
                            <td>
                                <form method="POST" style="margin:0; display:flex; gap:8px;">
                                    <input type="hidden" name="productId" value="<?= $s['id'] ?>">
                                    <input type="number" step="0.01" name="price" value="<?= $s['price'] ?>" style="width:80px; padding:4px;" required>
                                    <input type="number" name="stock" value="<?= $s['stock'] ?>" style="width:60px; padding:4px;" required>
                                    <button type="submit" name="editProduct" class="btn-outline" style="padding:4px 8px; font-size:0.75rem; background:#eff6ff;">Edit Listing</button>
                                </form>
                            </td>
                            <td><a href="adminDashboard.php?deleteProduct=<?= $s['id'] ?>" onclick="return confirm('Wipe listing?');" style="color:#dc2626; font-weight:600; text-decoration:none;">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>