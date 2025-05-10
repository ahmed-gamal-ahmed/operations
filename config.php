<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'product_scanner');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $sql)) {
    mysqli_select_db($conn, DB_NAME);
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Create tables if they don't exist
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barcode VARCHAR(255) NOT NULL,
    product_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating products table: " . mysqli_error($conn));
}

$sql = "CREATE TABLE IF NOT EXISTS scanned_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barcode VARCHAR(255) NOT NULL,
    product_data JSON NOT NULL,
    scanner_name VARCHAR(255),
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating scanned_products table: " . mysqli_error($conn));
}

$sql = "CREATE TABLE IF NOT EXISTS edited_barcodes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    edited_barcode VARCHAR(255) NOT NULL,
    original_barcode VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating edited_barcodes table: " . mysqli_error($conn));
}
?> 