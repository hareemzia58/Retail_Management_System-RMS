<?php include 'conn.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Order Form - Output</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial; margin: 20px; background: white; overflow-x: hidden; }
        .container { max-width: 1200px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        h2 { color: #0F4C81; margin-bottom: 20px; }
        h2 i { margin-right: 10px; color: #0F4C81; }
        .search-box { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 8px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .info-box { background: #e0f0ff; padding: 15px; margin: 15px 0; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        button { 
            padding: 10px 20px; 
            cursor: pointer; 
            margin-right: 10px; 
            background: linear-gradient(135deg, #0F4C81 0%, #0A3D66 100%);
            color: white; 
            border: none; 
            border-radius: 50px;
            font-weight: 500;
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
        input {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 50px;
            width: 200px;
        }
        input:focus {
            outline: none;
            border-color: #0F4C81;
        }
        .total { font-size: 16px; font-weight: bold; text-align: right; }
        .error { color: red; padding: 10px; background: #ffe0e0; margin: 10px 0; border-radius: 8px; }
        .error i { margin-right: 8px; }
        .invoice-list { margin-top: 30px; }
        .invoice-item { cursor: pointer; background: #f9f9f9; }
        .invoice-item:hover { background: #e0e0e0; }
        .print-btn {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <h2> Invoice</h2>
    
    <div class="search-box">
        <i class="fas fa-search" style="color: #0F4C81;"></i>
        <label>Enter Invoice No: </label>
        <input type="number" id="InvNo" placeholder="Enter invoice number" onkeypress="handleKeyPress(event)">
        <button onclick="searchOrder()"><i class="fas fa-search"></i> Search</button>
        <button onclick="showAllInvoices()"><i class="fas fa-file-invoice"></i> Show All Invoices</button>
        <button onclick="clearResult()"><i class="fas fa-eraser"></i> Clear</button>
    </div>
    
    <div id="result"></div>
    <div id="allInvoices" class="invoice-list"></div>
</div>

<script>
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            searchOrder();
        }
    }
    
    function searchOrder() {
        let invNo = document.getElementById('InvNo').value;
        if(!invNo) {
            alert('Please enter Invoice No');
            return;
        }
        
        document.getElementById('allInvoices').innerHTML = '';
        
        fetch(`get_order.php?InvNo=${invNo}`)
        .then(response => response.json())
        .then(data => {
            let resultDiv = document.getElementById('result');
            if(data.error) {
                resultDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-triangle"></i> ${data.error}</div>`;
                return;
            }
            
            let date = data.invoice.InvDate ? new Date(data.invoice.InvDate).toLocaleDateString() : 'N/A';
            
            let html = `
                <div class="info-box">
                    <h3><i class="fas fa-info-circle"></i> Invoice Information</h3>
                    <p><strong><i class="fas fa-hashtag"></i> Invoice No:</strong> ${data.invoice.InvNo}</p>
                    <p><strong><i class="fas fa-calendar-alt"></i> Date:</strong> ${date}</p>
                    <p><strong><i class="fas fa-user"></i> Customer:</strong> ${data.invoice.Cid} - ${data.invoice.CName}</p>
                    <p><strong><i class="fas fa-user-tie"></i> Employee:</strong> ${data.invoice.EmpId} - ${data.invoice.EmpName}</p>
                    <p><strong><i class="fas fa-truck"></i> Shipper:</strong> ${data.invoice.ShpID} - ${data.invoice.ShpName}</p>
                </div>
                
                <h3> Order Details</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            data.details.forEach(item => {
                let total = item.Qty * item.Price;
                html += `<tr>
                    <td>${item.Pid}</td>
                    <td>${item.Pname}</td>
                    <td>${item.Qty}</td>
                    <td>$${parseFloat(item.Price).toFixed(2)}</td>
                    <td>$${total.toFixed(2)}</td>
                </tr>`;
            });
            
            html += `</tbody>
                </table>
                <div class="total">Total Amount: $${parseFloat(data.invoice.TotalAmount).toFixed(2)}</div>
                <div class="print-btn">
                    <button onclick="window.print()"><i class="fas fa-print"></i> Print Invoice</button>
                </div>
            `;
            
            resultDiv.innerHTML = html;
        });
    }
    
    function showAllInvoices() {
        document.getElementById('result').innerHTML = '';
        document.getElementById('InvNo').value = '';
        
        fetch('get_all_invoices.php')
        .then(response => response.json())
        .then(data => {
            let html = `
                <h3> All Invoices</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Employee</th>
                            <th>Shipper</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            data.forEach(inv => {
                let date = inv.InvDate ? new Date(inv.InvDate).toLocaleDateString() : 'N/A';
                html += `<tr class="invoice-item" onclick="viewInvoice(${inv.InvNo})">
                    <td>${inv.InvNo}</td>
                    <td>${date}</td>
                    <td>${inv.Cid} - ${inv.CName}</td>
                    <td>${inv.EmpId} - ${inv.EmpName}</td>
                    <td>${inv.ShpID} - ${inv.ShpName}</td>
                    <td>$${parseFloat(inv.TotalAmount).toFixed(2)}</td>
                    <td><button onclick="event.stopPropagation(); viewInvoice(${inv.InvNo})"><i class="fas fa-eye"></i> View</button></td>
                </tr>`;
            });
            
            html += `</tbody>
                </table>
                <p><small><i class="fas fa-info-circle"></i> Click on any row to view invoice details</small></p>
            `;
            document.getElementById('allInvoices').innerHTML = html;
        });
    }
    
    function viewInvoice(invNo) {
        document.getElementById('InvNo').value = invNo;
        searchOrder();
        document.getElementById('allInvoices').innerHTML = '';
    }
    
    function clearResult() {
        document.getElementById('result').innerHTML = '';
        document.getElementById('allInvoices').innerHTML = '';
        document.getElementById('InvNo').value = '';
    }
    showAllInvoices();
</script>
</body>
</html>