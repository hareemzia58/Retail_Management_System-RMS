<?php
include 'conn.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Get the next available Product ID
$sql = "SELECT ISNULL(MAX(Pid), 100) + 1 AS NextID FROM PRODUCT";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$nextId = $row['NextID'];

// Insert new product
$sql2 = "INSERT INTO PRODUCT (Pid, Pname, PPrice) VALUES (?, ?, ?)";
$params = [$nextId, $data['Pname'], $data['PPrice']];
$stmt2 = sqlsrv_query($conn, $sql2, $params);

if($stmt2 === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to add product: ' . print_r(sqlsrv_errors(), true)]);
    exit;
}

// Also add to STORE_STOCK with initial quantity 0
$sql3 = "INSERT INTO STORE_STOCK (Pid, QtyOnHand, ReorderLevel, LastRestockDate) VALUES (?, 0, 10, GETDATE())";
$stmt3 = sqlsrv_query($conn, $sql3, [$nextId]);

echo json_encode(['success' => true, 'message' => 'Product added successfully! ID: ' . $nextId]);
?>