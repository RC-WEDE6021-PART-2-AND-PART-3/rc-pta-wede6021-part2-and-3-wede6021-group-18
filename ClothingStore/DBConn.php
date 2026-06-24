<?php
// Database connection file
// Connects PHP application to MySQL database

$host = "localhost";
$user = "root";
$password = "";
$dbname = "ClothingStore";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>