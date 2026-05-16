<?php
include 'conn.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

sqlsrv_begin_transaction($conn);

try {
    // Insert into INVOICE
    $sql1 = "INSERT INTO INVOICE (InvNo, InvDate, TotalAmount, Cid, EmpId, ShpID) 
             VALUES (?, ?, ?, ?, ?, ?)";
    $params1 = [$data['InvNo'], $data['InvDate'], $data['TotalAmount'], 
                $data['Cid'], $data['EmpId'], $data['ShpID']];
    $stmt1 = sqlsrv_query($conn, $sql1, $params1);
    
    if($stmt1 === false) throw new Exception("Failed to save invoice");
    
    // Insert into ORDER_DETAIL
    foreach($data['items'] as $item) {
        $sql2 = "INSERT INTO ORDER_DETAIL (InvNo, Pid, Qty, Price, Discount) VALUES (?, ?, ?, ?, 0)";
        $params2 = [$data['InvNo'], $item['Pid'], $item['Qty'], $item['Price']];
        $stmt2 = sqlsrv_query($conn, $sql2, $params2);
        if($stmt2 === false) throw new Exception("Failed to save order details");
    }
    
    sqlsrv_commit($conn);
    echo json_encode(['success' => true, 'message' => 'Order saved successfully!']);
    
} catch(Exception $e) {
    sqlsrv_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>