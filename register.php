<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'DBConn.php';

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'faq';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Information Center - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function selectTab(evt, id) {
            var i, c, b;
            c = document.getElementsByClassName("tab-content");
            for (i = 0; i < c.length; i++) { c[i].className = "tab-content"; }
            b = document.getElementsByClassName("tab-btn");
            for (i = 0; i < b.length; i++) { b[i].className = "tab-btn"; }
            document.getElementById(id).className = "tab-content active";
            evt.currentTarget.className = "tab-btn active";
        }
    </script>
</head>
<body class="<?= getWatermarkClass() ?>">

<?php include 'navbar.php'; ?>

<div class="container" style="max-width: 850px;">
    <div class="card">
        <div class="tabs-nav">
            <button class="tab-btn <?= $activeTab === 'faq' ? 'active' : '' ?>" onclick="selectTab(event, 'faq-content')">Frequently Asked Questions</button>
            <button class="tab-btn <?= $activeTab === 'tcs' ? 'active' : '' ?>" onclick="selectTab(event, 'tcs-content')">Terms & Conditions</button>
        </div>

        <div id="faq-content" class="tab-content <?= $activeTab === 'faq' ? 'active' : '' ?>">
            <h2 class="card-title" style="text-align: left; font-size: 1.4rem;">Customer Support FAQ</h2>
            <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 15px;">
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 4px; color: var(--primary-blue);">Q: Why does my profile say "Pending" when I register?</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;">A: To maintain marketplace safety, all newly created consumer profiles are held securely for administrator validation evaluation before stock procurement functions unlock.</p>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 4px; color: var(--primary-blue);">Q: How long does local shipment handling take?</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;">A: Once verified via orderNum reference routing, items are securely packed and dispatched to major hubs within 2 to 4 corporate business days.</p>
                </div>
            </div>
        </div>

        <div id="tcs-content" class="tab-content <?= $activeTab === 'tcs' ? 'active' : '' ?>">
            <h2 class="card-title" style="text-align: left; font-size: 1.4rem;">Platform Terms & Agreements</h2>
            <div style="font-size: 0.9rem; line-height: 1.6; display: flex; flex-direction: column; gap: 15px; color: var(--text-main); margin-top: 15px;">
                <p><strong>1. Relational Integrity:</strong> Purchases executed across checkout portals are final. Real-time stock counts decrease instantly inside server database matrices to enforce atomic balance protections.</p>
                <p><strong>2. User Accounts:</strong> Sharing system profiles or manipulating checkout forms using corrupted parameter arrays will cause account rows to be changed to suspended or dropped states.</p>
            </div>
        </div>

    </div>
</div>

</body>
</html>