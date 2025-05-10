<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['edited_barcodes']) && is_array($data['edited_barcodes'])) {
        // Clear existing edited barcodes
        mysqli_query($conn, "TRUNCATE TABLE edited_barcodes");
        
        // Prepare statement for inserting edited barcodes
        $stmt = mysqli_prepare($conn, "INSERT INTO edited_barcodes (edited_barcode, original_barcode) VALUES (?, ?)");
        
        foreach ($data['edited_barcodes'] as $barcodePair) {
            $editedBarcode = $barcodePair[0];
            $originalBarcode = $barcodePair[1];
            
            mysqli_stmt_bind_param($stmt, "ss", $editedBarcode, $originalBarcode);
            mysqli_stmt_execute($stmt);
        }
        
        mysqli_stmt_close($stmt);
        
        echo json_encode(['success' => true, 'message' => 'Edited barcodes saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 