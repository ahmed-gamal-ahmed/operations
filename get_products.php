<?php
header('Content-Type: application/json');
require_once 'config.php';

$response = ['success' => false, 'products' => []];

try {
    $sql = "SELECT barcode, product_data FROM products";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = [
                'barcode' => $row['barcode'],
                'data' => json_decode($row['product_data'], true)
            ];
        }
        $response['success'] = true;
        $response['products'] = $products;
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?> 