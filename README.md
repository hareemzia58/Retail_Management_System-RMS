# Retail Management System - RMS

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![SQL Server](https://img.shields.io/badge/SQL%20Server-2016%2B-red.svg)](https://www.microsoft.com/sql-server)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow.svg)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

A comprehensive **database-driven retail management system** built with PHP, SQL Server, and modern web technologies. This system manages customers, products, orders, invoices, inventory, and suppliers through an intuitive web interface.

---

## Overview

The **Super Store ABC Retail Management System** is a full-featured web application designed to digitize and streamline retail store operations. It provides a complete solution for managing:

- **Customers** - Registration, order history, and spending tracking
- **Products** - Product catalog, pricing, and supplier information
- **Orders & Invoices** - Order creation, invoice generation, and printing
- **Inventory** - Stock tracking, purchase orders, and supplier management
- **Employees & Shippers** - Staff management and shipping partner coordination

---
*Create New Order*

<img width="2210" height="1228" alt="image" src="https://github.com/user-attachments/assets/cb9328ed-f03e-466e-846f-2033624493cb" />

*View Invoices*

<img width="2198" height="1274" alt="image" src="https://github.com/user-attachments/assets/06389a4f-f40f-44f3-862e-84954dcf8cab" />

*Manage Customers*

<img width="2202" height="1269" alt="image" src="https://github.com/user-attachments/assets/f062b477-fc0c-4906-8b57-5021727f8363" />

*Manage Products*

<img width="2216" height="1266" alt="image" src="https://github.com/user-attachments/assets/58598381-9bf2-44d6-b800-652c513ae8e3" />

## Features

### Customer Management
- View all customers with order count and total spending
- Search customers by ID or name (smart search - auto-detects input type)
- View detailed customer information including address and phone
- Display complete order history with invoice details
- Add new customers to the system with auto-generated IDs

### Product Management
- View all products with ID, name, and selling price
- Search products by ID or name (smart search - auto-detects input type)
- View product details with supplier information
- Display cost price from each supplier and last supply date
- Add new products to the catalog with auto-generated IDs

### Order Processing

| Feature | Description |
|---------|-------------|
| **Create New Order** | Dynamic order creation with multiple products per invoice |
| **Auto-calculation** | Automatic total calculation as products are added |
| **Invoice Generation** | Generate invoices with customer, employee, and shipper details |
| **View/Print Orders** | Search existing orders by invoice number |
| **All Invoices View** | Browse all invoices with click-to-view functionality |
| **Print Functionality** | Print-ready invoice layout for customer receipts |

### Inventory Management
- Track stock received from suppliers with cost prices
- Multiple suppliers per product support
- Purchase order history tracking
- Profit calculation (selling price vs cost price)

### Smart Search
- Automatically detects whether you're searching by ID or name
- Partial name matching for flexible search
- Instant results display with supplier information

### User Interface
- Modern tab-based dashboard with Yale Blue (#0F4C81) theme
- Fully rounded buttons and tabs with gradient effects
- Sub-tab navigation for View and Add actions
- Keyboard support (press Enter to search)
- Print-friendly invoice layout
- Responsive design for desktop and tablet devices

---

## Technology Stack

| Component | Technology |
|-----------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | Microsoft SQL Server 2016+ |
| **Frontend** | HTML5, CSS3, JavaScript (ES6) |
| **Server** | Apache / XAMPP |
| **API Style** | RESTful endpoints with JSON responses |

---

## Installation Guide

### Prerequisites

| Requirement | Version |
|-------------|---------|
| Web Server | Apache / XAMPP |
| PHP | 7.4 or higher |
| SQL Server | 2016 or higher (including Express) |
| SQLSRV PHP Extension | Enabled |
| Git | (Optional, for cloning) |

### Step-by-Step Installation

#### 1. Clone the Repository

```bash
git clone https://github.com/hareemzia58/Retail_management_system-RMS.git
cd superstore-abc
```
#### 2. Configure Database Connection
Edit `php/db_connect.php`:

```php
<?php
$serverName = "YOUR_SERVER_NAME\SQLEXPRESS";  // Your SQL Server instance (server name)
$options = [
    "Database" => "IvorPaineHospital",
    "Uid"      => "your_username",     // Leave empty for Windows Auth
    "PWD"      => "your_password",     // Leave empty for Windows Auth
    "TrustServerCertificate" => true
];
?>
```
#### 3. Create and Populate Database
Run the SQL script using SQL Server Management Studio (SSMS):

```sql
-- To create tables and insert sample data, run:
SuperStore_DDL.sql
```
#### 4. Configure Web Server using XAMPP

Place project folder in C:\xampp\htdocs\

Access via: http://localhost//src/index.php

#### 5. Enable PHP SQLSRV Extension
In your php.ini file, uncomment or add:

```ini
extension=php_sqlsrv_74_ts_x64.dll
extension=php_pdo_sqlsrv_74_ts_x64.dll
```
Note: The exact filename depends on your PHP version. Restart your web server after changes.
