<?php
include 'conn.php';
header('Content-Type: application/json');

$sql = "SELECT i.*, c.CName, e.EmpName, s.ShpName 
        FROM INVOICE i
        INNER JOIN CUSTOMER c ON i.Cid = c.Cid
        INNER JOIN EMPLOYEE e ON i.EmpId = e.EmpId
        INNER JOIN SHIPPER s ON i.ShpID = s.ShpID
        ORDER BY i.InvDate DESC, i.InvNo DESC";
        
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to fetch invoices']);
    exit;
}

$invoices = [];
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Convert date to string format
    if ($row['InvDate'] instanceof DateTime) {
        $row['InvDate'] = $row['InvDate']->format('Y-m-d');
    }
    $invoices[] = $row;
}

echo json_encode($invoices);
?>