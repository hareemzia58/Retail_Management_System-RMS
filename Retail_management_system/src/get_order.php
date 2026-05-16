<?php
include 'conn.php';
header('Content-Type: application/json');

$InvNo = $_GET['InvNo'];

// Get invoice with joins
$sql = "SELECT i.*, c.CName, e.EmpName, s.ShpName 
        FROM INVOICE i
        INNER JOIN CUSTOMER c ON i.Cid = c.Cid
        INNER JOIN EMPLOYEE e ON i.EmpId = e.EmpId
        INNER JOIN SHIPPER s ON i.ShpID = s.ShpID
        WHERE i.InvNo = ?";
$stmt = sqlsrv_query($conn, $sql, [$InvNo]);

if($stmt === false || sqlsrv_has_rows($stmt) == 0) {
    echo json_encode(['error' => 'Invoice not found']);
    exit;
}

$invoice = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Get order details
$sql2 = "SELECT od.*, p.Pname 
         FROM ORDER_DETAIL od
         INNER JOIN PRODUCT p ON od.Pid = p.Pid
         WHERE od.InvNo = ?";
$stmt2 = sqlsrv_query($conn, $sql2, [$InvNo]);
$details = [];
while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $details[] = $row;
}

echo json_encode(['invoice' => $invoice, 'details' => $details]);
?>