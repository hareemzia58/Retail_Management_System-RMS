<?php include 'conn.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            font-family: Arial; 
            margin: 0; 
            padding: 20px; 
            background: white;
            overflow-x: hidden;
        }
        .container { 
            max-width: 1200px; 
            margin: auto; 
            background: white; 
        }
        h2 {
            color: #0F4C81;
            margin-bottom: 20px;
        }
        h2 i {
            margin-right: 10px;
            color: #0F4C81;
        }
        
        /* Sub Tabs Style - Fully rounded like main tabs */
        .sub-tabs {
            display: inline-flex;
            gap: 8px;
            margin-bottom: 25px;
            background: #f0f2f5;
            padding: 6px;
            border-radius: 60px;
        }
        .sub-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            font-size: 14px;
            font-weight: 500;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #4A5568;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .sub-tab-btn i {
            font-size: 16px;
        }
        .sub-tab-btn:hover {
            background: rgba(15, 76, 129, 0.1);
            color: #0F4C81;
        }
        .sub-tab-btn:hover i {
            color: #0F4C81;
        }
        .sub-tab-btn.active {
            background: linear-gradient(135deg, #0F4C81 0%, #0A3D66 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(15, 76, 129, 0.25);
        }
        .sub-tab-btn.active i {
            color: white;
        }
        
        .sub-tab-content {
            display: none;
            animation: fadeIn 0.3s;
        }
        .sub-tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Form Styles */
        .form-container {
            background: #f5f5f5;
            padding: 25px;
            border-radius: 12px;
            max-width: 500px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        .form-group label i {
            margin-right: 8px;
            color: #0F4C81;
            width: 20px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 50px;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0F4C81;
            box-shadow: 0 0 0 2px rgba(15, 76, 129, 0.2);
        }
        
        /* Search Box */
        .search-box { 
            background: #f5f5f5; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 8px; 
        }
        .product-info { 
            background: #e0f0ff; 
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 8px; 
        }
        .supplier-info { 
            background: #ffffe0; 
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 8px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 15px 0; 
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background: #f0f0f0; 
        }
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
        .error { 
            color: red; 
            padding: 10px; 
            background: #ffe0e0; 
            margin: 10px 0; 
            border-radius: 5px; 
        }
        .success { 
            color: green; 
            padding: 10px; 
            background: #e0ffe0; 
            margin: 10px 0; 
            border-radius: 5px; 
        }
        .product-list { 
            margin-top: 30px; 
        }
        .search-options {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        input {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 50px;
            width: 300px;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #0F4C81;
            box-shadow: 0 0 0 2px rgba(15, 76, 129, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <h2> Manage Products</h2>
    
    <div class="sub-tabs">
        <button class="sub-tab-btn active" onclick="switchSubTab('view', this)">
            <i class="fas fa-list-ul"></i>
            <span>View Products</span>
        </button>
        <button class="sub-tab-btn" onclick="switchSubTab('add', this)">
            <i class="fas fa-plus-circle"></i>
            <span>Add New Product</span>
        </button>
    </div>
    
    <!-- View Product Tab -->
    <div id="view-tab" class="sub-tab-content active">
        <div class="search-box">
            <div class="search-options">
                <i class="fas fa-search" style="color: #0F4C81; font-size: 18px;"></i>
                <input type="text" id="searchValue" placeholder="Enter product ID or name..." onkeypress="handleKeyPress(event)">
                <button onclick="searchProduct()"><i class="fas fa-search"></i> Search</button>
                <button onclick="showAllProducts()"><i class="fas fa-boxes"></i> Show All Products</button>
                <button onclick="clearResults()"><i class="fas fa-eraser"></i> Clear</button>
            </div>
        </div>
        
        <div id="result"></div>
        <div id="allProducts" class="product-list"></div>
    </div>
    
    <!-- Add Product Tab -->
    <div id="add-tab" class="sub-tab-content">
        <div class="form-container">
            <h3 style="color: #0F4C81; margin-bottom: 20px;"> Add New Product</h3>
            <div id="addMessage"></div>
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Product Name *</label>
                <input type="text" id="prodName" placeholder="Enter product name">
            </div>
            <div class="form-group">
                <label><i class="fas fa-dollar-sign"></i> Selling Price *</label>
                <input type="number" id="prodPrice" step="0.01" placeholder="Enter selling price">
            </div>
            <button onclick="addProduct()"><i class="fas fa-save"></i> Save Product</button>
            <button onclick="clearAddForm()"><i class="fas fa-undo-alt"></i> Clear</button>
        </div>
    </div>
</div>

<script>
    // Sub Tab Switching - Same pattern as customer_master
    function switchSubTab(tabName, btnElement) {
        // Hide all tab contents
        document.querySelectorAll('.sub-tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.sub-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(`${tabName}-tab`).classList.add('active');
        
        // Add active class to clicked button
        btnElement.classList.add('active');
    }
    
    // ========== VIEW PRODUCT FUNCTIONS ==========
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            searchProduct();
        }
    }
    
    function searchProduct() {
        let searchValue = document.getElementById('searchValue').value.trim();
        
        if(!searchValue) {
            alert('Please enter a product ID or name');
            return;
        }
        
        document.getElementById('allProducts').innerHTML = '';
        
        let isNumeric = /^\d+$/.test(searchValue);
        let searchType = isNumeric ? 'id' : 'name';
        
        fetch(`get_product.php?searchType=${searchType}&searchValue=${encodeURIComponent(searchValue)}`)
        .then(response => response.json())
        .then(data => {
            let resultDiv = document.getElementById('result');
            if(data.error) {
                resultDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-triangle"></i> ${data.error}</div>`;
                return;
            }
            
            let html = `
                <div class="product-info">
                    <h3><i class="fas fa-info-circle"></i> Product Information</h3>
                    <p><strong><i class="fas fa-barcode"></i> ID:</strong> ${data.product.Pid}</p>
                    <p><strong><i class="fas fa-tag"></i> Name:</strong> ${data.product.Pname}</p>
                    <p><strong><i class="fas fa-dollar-sign"></i> Selling Price:</strong> $${parseFloat(data.product.PPrice).toFixed(2)}</p>
                </div>
            `;
            
            if(data.suppliers && data.suppliers.length > 0) {
                html += `<div class="supplier-info">
                    <h3><i class="fas fa-truck"></i> Supplier Information</h3>
                    <table class="supplier-table" style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="border:1px solid #ccc; padding:8px; background:#f0f0f0;"><i class="fas fa-id-card"></i> Supplier ID</th>
                                <th style="border:1px solid #ccc; padding:8px; background:#f0f0f0;"><i class="fas fa-building"></i> Supplier Name</th>
                                <th style="border:1px solid #ccc; padding:8px; background:#f0f0f0;"><i class="fas fa-dollar-sign"></i> Cost Price</th>
                                <th style="border:1px solid #ccc; padding:8px; background:#f0f0f0;"><i class="fas fa-calendar-alt"></i> Last Supply Date</th>
                            </tr>
                        </thead>
                        <tbody>`;
                
                data.suppliers.forEach(sup => {
                    let date = sup.LastSupplyDate ? new Date(sup.LastSupplyDate).toLocaleDateString() : 'N/A';
                    html += `<tr>
                        <td>${sup.SupID}</td>
                        <td>${sup.SupName}</td>
                        <td>$${parseFloat(sup.Price).toFixed(2)}</td>
                        <td>${date}</td>
                    </tr>`;
                });
                
                html += `</tbody>
                    </table>
                </div>`;
            } else {
                html += `<p><i class="fas fa-info-circle"></i> No supplier information available.</p>`;
            }
            
            resultDiv.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-triangle"></i> Error fetching product data. Please try again.</div>`;
        });
    }
    
    function showAllProducts() {
        document.getElementById('result').innerHTML = '';
        document.getElementById('searchValue').value = '';
        
        fetch('get_all_products.php')
        .then(response => response.json())
        .then(data => {
            let html = `<h3> All Products</h3>
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border:1px solid #ccc; padding:8px; background:#0F4C81; color:white;"> ID</th>
                            <th style="border:1px solid #ccc; padding:8px; background:#0F4C81; color:white;"> Name</th>
                            <th style="border:1px solid #ccc; padding:8px; background:#0F4C81; color:white;"> Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            data.forEach(prod => {
                html += `<tr>
                    <td>${prod.Pid}</td>
                    <td>${prod.Pname}</td>
                    <td>$${parseFloat(prod.PPrice).toFixed(2)}</td>
                </tr>`;
            });
            
            html += `</tbody>
                </table>`;
            document.getElementById('allProducts').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('allProducts').innerHTML = '<div class="error"><i class="fas fa-exclamation-triangle"></i> Error fetching products. Please try again.</div>';
        });
    }
    
    function clearResults() {
        document.getElementById('result').innerHTML = '';
        document.getElementById('allProducts').innerHTML = '';
        document.getElementById('searchValue').value = '';
    }
    
    // ========== ADD PRODUCT FUNCTIONS ==========
    function addProduct() {
        let name = document.getElementById('prodName').value.trim();
        let price = document.getElementById('prodPrice').value.trim();
        
        if(!name) {
            document.getElementById('addMessage').innerHTML = '<div class="error"><i class="fas fa-exclamation-circle"></i> Please enter product name</div>';
            return;
        }
        if(!price || price <= 0) {
            document.getElementById('addMessage').innerHTML = '<div class="error"><i class="fas fa-exclamation-circle"></i> Please enter a valid selling price</div>';
            return;
        }
        
        fetch('add_product.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({Pname: name, PPrice: price})
        })
        .then(response => response.json())
        .then(data => {
            let msgDiv = document.getElementById('addMessage');
            if(data.success) {
                msgDiv.innerHTML = `<div class="success"><i class="fas fa-check-circle"></i> ${data.message}</div>`;
                clearAddForm();
                setTimeout(() => {
                    // Find and click the view tab button
                    document.querySelector('.sub-tab-btn').click();
                    showAllProducts();
                }, 1500);
            } else {
                msgDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-circle"></i> ${data.message}</div>`;
            }
        });
    }
    
    function clearAddForm() {
        document.getElementById('prodName').value = '';
        document.getElementById('prodPrice').value = '';
        document.getElementById('addMessage').innerHTML = '';
    }
    
    // Load all products on page load
    showAllProducts();
</script>
</body>
</html>