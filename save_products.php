<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['products']) && is_array($data['products'])) {
        // Clear existing products
        mysqli_query($conn, "TRUNCATE TABLE products");
        
        // Prepare statement for inserting products
        $stmt = mysqli_prepare($conn, "INSERT INTO products (barcode, product_data) VALUES (?, ?)");
        
        foreach ($data['products'] as $product) {
            $barcode = $product['barcode'];
            $productData = json_encode($product['data']);
            
            mysqli_stmt_bind_param($stmt, "ss", $barcode, $productData);
            mysqli_stmt_execute($stmt);
        }
        
        mysqli_stmt_close($stmt);
        
        echo json_encode(['success' => true, 'message' => 'Products saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 