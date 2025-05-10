<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['scanner_name'])) {
        $scannerName = $data['scanner_name'];
        
        // Get all scanned products
        $result = mysqli_query($conn, "SELECT * FROM scanned_products ORDER BY scanned_at DESC");
        $products = [];
        
        // Get headers from the first product
        $firstProduct = mysqli_fetch_assoc($result);
        if ($firstProduct) {
            $headers = array_keys(json_decode($firstProduct['product_data'], true));
            $products[] = $headers;
            
            // Reset the result pointer
            mysqli_data_seek($result, 0);
            
            // Add all products
            while ($row = mysqli_fetch_assoc($result)) {
                $productData = json_decode($row['product_data'], true);
                $products[] = array_values($productData);
            }
            
            echo json_encode(['success' => true, 'products' => $products]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No products found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Scanner name is required']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 