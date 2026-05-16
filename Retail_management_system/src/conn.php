<?php

$serverName = "LAPTOP-OGK91IGE\\SQLEXPRESS"; //Change the server name to your server name
$connectionOptions = array(
    "Database" => "SuperStoreDB",
    "Uid" => "",
    "PWD" => "",
    "TrustServerCertificate" => true
);

// Connect
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT * FROM product";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

?>