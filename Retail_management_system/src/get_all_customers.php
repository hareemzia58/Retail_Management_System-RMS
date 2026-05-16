<?php
include 'conn.php';
header('Content-Type: application/json');

$sql = "SELECT c.*, 
        COUNT(i.InvNo) AS OrderCount, 
        ISNULL(SUM(i.TotalAmount), 0) AS TotalSpent
        FROM CUSTOMER c
        LEFT JOIN INVOICE i ON c.Cid = i.Cid
        GROUP BY c.Cid, c.CName, c.TelNo, c.Address
        ORDER BY c.Cid";
$stmt = sqlsrv_query($conn, $sql);
$customers = [];
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $customers[] = $row;
}
echo json_encode($customers);
?>