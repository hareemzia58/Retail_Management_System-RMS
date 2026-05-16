<?php
// index.php - Main Dashboard for Super Store Management System
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Store Management System</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        
        /* Header - Yale Blue (#0F4C81) */
        .header {
            background: linear-gradient(135deg, #0F4C81 0%, #0A3D66 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            text-align: center;
            font-size: 28px;
        }
        
        .header p {
            text-align: center;
            margin-top: 5px;
            opacity: 0.9;
        }
        
        /* Navigation Tabs - Fully rounded like the example */
        .nav-tabs {
            background: white;
            border-radius: 60px;
            padding: 6px;
            margin: 20px 20px 0 20px;
            display: inline-flex;
            gap: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }
        
        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 500;
            color: #4A5568;
            text-decoration: none;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
            cursor: pointer;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .tab-btn .material-icons {
            font-size: 20px;
        }
        
        .tab-btn:hover {
            background: rgba(15, 76, 129, 0.08);
            color: #0F4C81;
        }
        
        .tab-btn.active {
            background: linear-gradient(135deg, #0F4C81 0%, #0A3D66 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(15, 76, 129, 0.25);
        }
        
        /* Content Area */
        .content {
            padding: 20px;
            min-height: calc(100vh - 180px);
            background: #f0f2f5;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
        
        /* Utility Classes */
        .loading {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
        }
        
        iframe {
            width: 100%;
            height: 80vh;
            border: none;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Center the tabs container */
        .tabs-wrapper {
            display: flex;
            justify-content: center;
            background: #f0f2f5;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Super Store ABC</h1>
        <p>Retail Management System</p>
    </div>
    
    <div class="tabs-wrapper">
        <div class="nav-tabs">
            <button class="tab-btn active" data-tab="input" onclick="switchTab('input', this)">
                <span class="material-icons">add_shopping_cart</span>
                <span>Create New Order</span>
            </button>
            <button class="tab-btn" data-tab="output" onclick="switchTab('output', this)">
                <span class="material-icons">visibility</span>
                <span>View/Print Order</span>
            </button>
            <button class="tab-btn" data-tab="customer" onclick="switchTab('customer', this)">
                <span class="material-icons">people</span>
                <span>Customer Management</span>
            </button>
            <button class="tab-btn" data-tab="product" onclick="switchTab('product', this)">
                <span class="material-icons">inventory_2</span>
                <span>Product Management</span>
            </button>
        </div>
    </div>
    
    <div class="content">
        <!-- Tab 1: Create New Order -->
        <div id="tab-input" class="tab-content active">
            <iframe src="order_input.php"></iframe>
        </div>
        
        <!-- Tab 2: View/Print Order -->
        <div id="tab-output" class="tab-content">
            <iframe src="order_output.php"></iframe>
        </div>
        
        <!-- Tab 3: Customer Management -->
        <div id="tab-customer" class="tab-content">
            <iframe src="customer_master.php"></iframe>
        </div>
        
        <!-- Tab 4: Product Management -->
        <div id="tab-product" class="tab-content">
            <iframe src="product_master.php"></iframe>
        </div>
    </div>

    
    <script>
        function switchTab(tabName, clickedButton) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(`tab-${tabName}`).classList.add('active');
            
            // Add active class to the clicked button
            clickedButton.classList.add('active');
        }
    </script>
</body>
</html>