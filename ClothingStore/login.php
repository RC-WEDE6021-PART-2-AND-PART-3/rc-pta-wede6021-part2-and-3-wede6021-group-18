<?php
// ==========================================
// LOGIN PAGE - Email & Password Only
// ==========================================

// Start session
session_start();

// Show errors (for development - remove before submission if needed)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'DBConn.php';

// Message variable for feedback
$message = "";

// ==========================================
// HANDLE LOGIN
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Capture user input
    $userEmail = $_POST['email'];
    $userPassword = md5($_POST['password']); // Hash password

    // Check if user exists
    $sql = "SELECT * FROM tblUser WHERE email='$userEmail'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        // Fetch user data
        $user = $result->fetch_assoc();

        // Check password
        if ($user['password'] !== $userPassword) {
            $message = "Incorrect password. Please try again.";
        }

        // Check if user is approved
        elseif ($user['status'] === "Pending") {
            $message = "Your account is pending approval by the administrator.";
        }

        // Successful login
        else {
            // Store user info in session
            $_SESSION['userName'] = $user['name'];
            $_SESSION['userEmail'] = $user['email'];

            // Redirect to dashboard
            header("Location: userDashboard.php");
            exit();
        }

    } else {
        $message = "No account found. Please register first.";
    }
}
?>

<!-- ========================================== -->
<!-- UI DESIGN -->
<!-- ========================================== -->

<link rel="stylesheet" href="css/style.css">
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="card">

        <h2 class="title">User Login</h2>

        <!-- Login Form -->
        <form method="POST">

            <!-- Email -->
            <label>Email Address</label>
            <input type="email" 
                   name="email" 
                   placeholder="Enter your email"
                   value="<?= $_POST['email'] ?? '' ?>" 
                   required>

            <!-- Password -->
            <label>Password</label>
            <input type="password" 
                   name="password" 
                   placeholder="Enter your password" 
                   required>

            <!-- Submit -->
            <button type="submit">Login</button>

        </form>

        <!-- Feedback Message -->
        <?php if (!empty($message)): ?>
            <div class="message error">
                <?= $message ?>
            </div>
        <?php endif; ?>

    </div>
</div>