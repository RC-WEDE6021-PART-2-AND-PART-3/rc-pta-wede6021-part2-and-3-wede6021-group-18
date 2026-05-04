<?php
// Creates tables and loads data from text files

include 'DBConn.php';

// Drop tables
$conn->query("DROP TABLE IF EXISTS tblUser");
$conn->query("DROP TABLE IF EXISTS tblAdmin");
$conn->query("DROP TABLE IF EXISTS tblClothes");
$conn->query("DROP TABLE IF EXISTS tblAorder");

// Create tables
$conn->query("CREATE TABLE tblUser (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    status VARCHAR(20)
)");

$conn->query("CREATE TABLE tblAdmin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100),
    password VARCHAR(255)
)");

$conn->query("CREATE TABLE tblClothes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
    image VARCHAR(100)
)");

$conn->query("CREATE TABLE tblAorder (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    itemId INT,
    quantity INT
)");

// Load user data
$file = fopen("data/userData.txt", "r");
while(($line = fgetcsv($file)) !== FALSE){
    $conn->query("INSERT INTO tblUser (name,email,password,status)
    VALUES ('$line[0]','$line[1]','$line[2]','$line[3]')");
}
fclose($file);

// Load admin data
$file = fopen("data/adminData.txt", "r");
while(($line = fgetcsv($file)) !== FALSE){
    $conn->query("INSERT INTO tblAdmin (email,password)
    VALUES ('$line[0]','$line[1]')");
}
fclose($file);

// Load clothes data
$file = fopen("data/clothesData.txt", "r");
while(($line = fgetcsv($file)) !== FALSE){
    $conn->query("INSERT INTO tblClothes (itemName,description,price,image)
    VALUES ('$line[0]','$line[1]','$line[2]','$line[3]')");
}
fclose($file);

echo "Database loaded successfully!";
?>