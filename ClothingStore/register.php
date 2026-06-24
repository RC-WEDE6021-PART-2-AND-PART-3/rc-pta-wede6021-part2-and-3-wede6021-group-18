<?php
include 'DBConn.php';

$msg="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);

    $conn->query("INSERT INTO tblUser (name,email,password,status)
    VALUES ('$name','$email','$password','Pending')");

    $msg="Registered! Await admin approval.";
}
?>

<link rel="stylesheet" href="css/style.css">
<?php include 'navbar.php'; ?>

<div class="container">
<div class="card">
<h2 class="title">Register</h2>

<form method="POST">

    <label>Full Name</label>
    <input type="text" name="name" placeholder="Enter your full name" required>

    <label>Email Address</label>
    <input type="email" name="email" placeholder="Enter your email" required>

    <label>Password</label>
    <input type="password" name="password" placeholder="Enter your password" required>

    <button type="submit">Register</button>

</form>

<p><?= $msg ?></p>
</div>
</div>