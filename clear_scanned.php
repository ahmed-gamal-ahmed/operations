<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (mysqli_query($conn, "TRUNCATE TABLE scanned_products")) {
        echo json_encode(['success' => true, 'message' => 'Scanned products cleared successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error clearing scanned products']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 