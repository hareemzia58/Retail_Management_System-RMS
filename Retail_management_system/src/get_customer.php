<?php
include 'conn.php';
header('Content-Type: application/json');

$searchType = $_GET['searchType'] ?? 'id';
$searchValue = $_GET['searchValue'] ?? '';

// Determine if searching by ID or Name
if ($searchType == 'id') {
    // Search by Customer ID
    $sql = "SELECT * FROM CUSTOMER WHERE Cid = ?";
    $params = [$searchValue];
} else {
    // Search by Customer Name (partial match allowed)
    $sql = "SELECT * FROM CUSTOMER WHERE CName LIKE ?";
    $params = ["%$searchValue%"];
}

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false || sqlsrv_has_rows($stmt) == 0) {
    echo json_encode(['error' => 'Customer not found']);
    exit;
}

// If searching by name and multiple results, return the first one (or handle multiple)
$customer = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Get all orders for this customer
$sql2 = "SELECT i.*, e.EmpName, s.ShpName 
         FROM INVOICE i
         INNER JOIN EMPLOYEE e ON i.EmpId = e.EmpId
         INNER JOIN SHIPPER s ON i.ShpID = s.ShpID
         WHERE i.Cid = ?
         ORDER BY i.InvDate DESC";
$stmt2 = sqlsrv_query($conn, $sql2, [$customer['Cid']]);
$orders = [];
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $orders[] = $row;
}

echo json_encode(['customer' => $customer, 'orders' => $orders]);
?>