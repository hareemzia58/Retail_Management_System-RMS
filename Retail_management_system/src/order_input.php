<?php
include 'conn.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Order Form - Input</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial; margin: 20px; background: white; }
        .container { max-width: 900px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        h2 { color: #0F4C81; margin-bottom: 20px; }
        h2 i { margin-right: 10px; color: #0F4C81; }
        .form-group { margin-bottom: 10px; }
        label { display: inline-block; width: 120px; }
        input, select { padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 50px; }
        input:focus, select:focus { outline: none; border-color: #0F4C81; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        button { 
            padding: 10px 20px; 
            margin-right: 10px; 
            background: linear-gradient(135deg, #0F4C81 0%, #0A3D66 100%);
            color: white; 
            border: none; 
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button i {
            margin-right: 8px;
        }
        button:hover { 
            background: linear-gradient(135deg, #0A3D66 0%, #082E4D 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 76, 129, 0.3);
        }
        .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 15px; }
        .success { color: green; padding: 10px; background: #e0ffe0; margin-bottom: 10px; border-radius: 8px; }
        .success i { margin-right: 8px; }
        .error { color: red; padding: 10px; background: #ffe0e0; margin-bottom: 10px; border-radius: 8px; }
        .error i { margin-right: 8px; }
    </style>
</head>
<body>
<div class="container">
    <h2> New Order</h2>
    
    <div id="message"></div>
    
    <form id="orderForm">
        <div class="form-group">
            <label>Invoice No:</label>
            <input type="number" name="InvNo" id="InvNo" required>
        </div>
        <div class="form-group">
            <label>Invoice Date:</label>
            <input type="date" name="InvDate" id="InvDate" required>
        </div>
        <div class="form-group">
            <label>Customer:</label>
            <select name="Cid" id="Cid" required>
                <option value="">Select Customer</option>
                <?php
                $sql = "SELECT Cid, CName FROM CUSTOMER ORDER BY Cid";
                $stmt = sqlsrv_query($conn, $sql);
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='{$row['Cid']}'>{$row['Cid']} - {$row['CName']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Employee:</label>
            <select name="EmpId" id="EmpId" required>
                <option value="">Select Employee</option>
                <?php
                $sql = "SELECT EmpId, EmpName FROM EMPLOYEE ORDER BY EmpId";
                $stmt = sqlsrv_query($conn, $sql);
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='{$row['EmpId']}'>{$row['EmpId']} - {$row['EmpName']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Shipper:</label>
            <select name="ShpID" id="ShpID" required>
                <option value="">Select Shipper</option>
                <?php
                $sql = "SELECT ShpID, ShpName FROM SHIPPER ORDER BY ShpID";
                $stmt = sqlsrv_query($conn, $sql);
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='{$row['ShpID']}'>{$row['ShpID']} - {$row['ShpName']}</option>";
                }
                ?>
            </select>
        </div>
        
        <h3> Order Details</h3>
        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 15px;">
            <select id="productSelect" style="width: 300px;">
                <option value="">Select Product</option>
                <?php
                $sql = "SELECT Pid, Pname, PPrice FROM PRODUCT ORDER BY Pid";
                $stmt = sqlsrv_query($conn, $sql);
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='{$row['Pid']}' data-price='{$row['PPrice']}'>{$row['Pid']} - {$row['Pname']} (\${$row['PPrice']})</option>";
                }
                ?>
            </select>
            <input type="number" id="quantity" placeholder="Quantity" style="width: 120px;">
            <button type="button" onclick="addProduct()"><i class="fas fa-plus-circle"></i> Add Product</button>
        </div>
        
        <table id="itemsTable">
            <thead>
                <tr><th>Product ID</th><th>Product Name</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>
            </thead>
            <tbody id="itemsBody"></tbody>
        </table>
        
        <div class="total">Total Amount: $<span id="totalAmount">0.00</span></div>
        
        <button type="button" onclick="saveOrder()"><i class="fas fa-save"></i> Save Order</button>
        <button type="button" onclick="clearForm()"><i class="fas fa-undo-alt"></i> Clear</button>
    </form>
</div>

<script>
    let items = [];
    
    function addProduct() {
        let select = document.getElementById('productSelect');
        let productId = select.value;
        let productName = select.options[select.selectedIndex].text;
        let price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price'));
        let quantity = parseInt(document.getElementById('quantity').value);
        
        if(!productId || !quantity || quantity <= 0) {
            alert('Please select product and enter valid quantity');
            return;
        }
        
        items.push({Pid: productId, Pname: productName, Qty: quantity, Price: price});
        displayItems();
        calculateTotal();
        document.getElementById('quantity').value = '';
        select.value = '';
    }
    
    function displayItems() {
        let body = document.getElementById('itemsBody');
        body.innerHTML = '';
        items.forEach((item, index) => {
            let total = item.Qty * item.Price;
            body.innerHTML += `<tr>
                <td>${item.Pid}</td>
                <td>${item.Pname}</td>
                <td>${item.Qty}</td>
                <td>$${item.Price.toFixed(2)}</td>
                <td>$${total.toFixed(2)}</td>
                <td><button onclick="removeItem(${index})"><i class="fas fa-trash-alt"></i> Remove</button></td>
            </tr>`;
        });
    }
    
    function removeItem(index) {
        items.splice(index, 1);
        displayItems();
        calculateTotal();
    }
    
    function calculateTotal() {
        let total = items.reduce((sum, item) => sum + (item.Qty * item.Price), 0);
        document.getElementById('totalAmount').innerText = total.toFixed(2);
    }
    
    function clearForm() {
        items = [];
        displayItems();
        calculateTotal();
        document.getElementById('InvNo').value = '';
        document.getElementById('InvDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('Cid').value = '';
        document.getElementById('EmpId').value = '';
        document.getElementById('ShpID').value = '';
        document.getElementById('message').innerHTML = '';
    }
    
    function saveOrder() {
        let orderData = {
            InvNo: document.getElementById('InvNo').value,
            InvDate: document.getElementById('InvDate').value,
            Cid: document.getElementById('Cid').value,
            EmpId: document.getElementById('EmpId').value,
            ShpID: document.getElementById('ShpID').value,
            TotalAmount: document.getElementById('totalAmount').innerText,
            items: items
        };
        
        if(!orderData.InvNo || !orderData.Cid || !orderData.EmpId || !orderData.ShpID || items.length == 0) {
            alert('Please fill all fields and add at least one product');
            return;
        }
        
        fetch('save_order.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            let msgDiv = document.getElementById('message');
            if(data.success) {
                msgDiv.innerHTML = `<div class="success"><i class="fas fa-check-circle"></i> ${data.message}</div>`;
                setTimeout(() => clearForm(), 2000);
            } else {
                msgDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-circle"></i> ${data.message}</div>`;
            }
        });
    }
    
    // Set today's date as default
    document.getElementById('InvDate').valueAsDate = new Date();
</script>
</body>
</html>