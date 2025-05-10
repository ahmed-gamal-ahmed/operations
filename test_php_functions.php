<?php
require_once 'config.php';

function testEndpoint($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Testing $url ($method):\n";
    echo "HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";
    
    return $response;
}

// Test data
$testProduct = [
    'barcode' => 'TEST123',
    'data' => ['TEST123', 'Test Product', 'Test Description', '10.99']
];

$testEditedBarcode = [
    'edited_barcode' => 'EDITED123',
    'original_barcode' => 'TEST123'
];

// Test save_products.php
echo "=== Testing save_products.php ===\n";
testEndpoint('http://localhost/product%20scanner/save_products.php', 'POST', [
    'products' => [$testProduct]
]);

// Test get_products.php
echo "=== Testing get_products.php ===\n";
testEndpoint('http://localhost/product%20scanner/get_products.php');

// Test save_scanned.php
echo "=== Testing save_scanned.php ===\n";
testEndpoint('http://localhost/product%20scanner/save_scanned.php', 'POST', [
    'barcode' => 'TEST123',
    'product_data' => $testProduct['data']
]);

// Test get_scanned.php
echo "=== Testing get_scanned.php ===\n";
testEndpoint('http://localhost/product%20scanner/get_scanned.php');

// Test save_edited_barcodes.php
echo "=== Testing save_edited_barcodes.php ===\n";
testEndpoint('http://localhost/product%20scanner/save_edited_barcodes.php', 'POST', [
    'edited_barcodes' => [$testEditedBarcode]
]);

// Test get_edited_barcodes.php
echo "=== Testing get_edited_barcodes.php ===\n";
testEndpoint('http://localhost/product%20scanner/get_edited_barcodes.php');

// Test export_scanned.php
echo "=== Testing export_scanned.php ===\n";
testEndpoint('http://localhost/product%20scanner/export_scanned.php', 'POST', [
    'scanner_name' => 'Test Scanner'
]);

// Test clear_scanned.php
echo "=== Testing clear_scanned.php ===\n";
testEndpoint('http://localhost/product%20scanner/clear_scanned.php', 'POST');
?> 