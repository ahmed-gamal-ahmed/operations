<?php
header('Content-Type: application/json');
require_once 'config.php';

$response = ['success' => false, 'scanned_products' => []];

try {
    $sql = "SELECT barcode, product_data FROM scanned_products";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $scanned_products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $scanned_products[] = [
                'barcode' => $row['barcode'],
                'data' => json_decode($row['product_data'], true)
            ];
        }
        $response['success'] = true;
        $response['scanned_products'] = $scanned_products;
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?> 