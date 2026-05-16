<?php
include 'conn.php';
header('Content-Type: application/json');

$searchType = $_GET['searchType'] ?? 'id';
$searchValue = $_GET['searchValue'] ?? '';

// Determine if searching by ID or Name
if ($searchType == 'id') {
    // Search by Product ID
    $sql = "SELECT * FROM PRODUCT WHERE Pid = ?";
    $params = [$searchValue];
} else {
    // Search by Product Name (partial match allowed)
    $sql = "SELECT * FROM PRODUCT WHERE Pname LIKE ?";
    $params = ["%$searchValue%"];
}

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || sqlsrv_has_rows($stmt) == 0) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}

$product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Get all suppliers for this product
$sql2 = "SELECT DISTINCT s.SupID, s.SupName, sd.Price, r.StInvDate AS LastSupplyDate
         FROM STOCK_IN_DETAIL sd
         INNER JOIN STOCK_IN_REC r ON sd.StInvNo = r.StInvNo
         INNER JOIN SUPPLIER s ON r.SupID = s.SupID
         WHERE sd.Pid = ?
         ORDER BY r.StInvDate DESC";
$stmt2 = sqlsrv_query($conn, $sql2, [$product['Pid']]);
$suppliers = [];
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $suppliers[] = $row;
}

echo json_encode(['product' => $product, 'suppliers' => $suppliers]);
?>