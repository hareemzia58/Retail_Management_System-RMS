<?php
include 'conn.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Get the next available Customer ID
$sql = "SELECT ISNULL(MAX(Cid), 100) + 1 AS NextID FROM CUSTOMER";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$nextId = $row['NextID'];

// Insert new customer
$sql2 = "INSERT INTO CUSTOMER (Cid, CName, TelNo, Address) VALUES (?, ?, ?, ?)";
$params = [$nextId, $data['CName'], $data['TelNo'] ?? '', $data['Address'] ?? ''];
$stmt2 = sqlsrv_query($conn, $sql2, $params);

if($stmt2 === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to add customer: ' . print_r(sqlsrv_errors(), true)]);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Customer added successfully! ID: ' . $nextId]);
?>