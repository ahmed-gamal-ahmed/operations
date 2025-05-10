<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['barcode']) && isset($data['product_data'])) {
        $barcode = $data['barcode'];
        $productData = json_encode($data['product_data']);
        $scannerName = isset($data['scanner_name']) ? $data['scanner_name'] : null;
        
        $stmt = mysqli_prepare($conn, "INSERT INTO scanned_products (barcode, product_data, scanner_name) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $barcode, $productData, $scannerName);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Scanned product saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving scanned product']);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 