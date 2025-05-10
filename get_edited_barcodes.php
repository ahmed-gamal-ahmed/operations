<?php
header('Content-Type: application/json');
require_once 'config.php';

$response = ['success' => false, 'edited_barcodes' => []];

try {
    $sql = "SELECT edited_barcode, original_barcode FROM edited_barcodes";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $edited_barcodes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $edited_barcodes[$row['edited_barcode']] = $row['original_barcode'];
        }
        $response['success'] = true;
        $response['edited_barcodes'] = $edited_barcodes;
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?> 