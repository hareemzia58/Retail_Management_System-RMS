<?php
include 'conn.php';
header('Content-Type: application/json');

$sql = "SELECT Pid, Pname, PPrice FROM PRODUCT ORDER BY Pid";
$stmt = sqlsrv_query($conn, $sql);
$products = [];
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $products[] = $row;
}
echo json_encode($products);
?>