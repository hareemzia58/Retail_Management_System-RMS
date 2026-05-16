USE master;
GO

-- Drop the entire database if it exists
IF EXISTS (SELECT name FROM sys.databases WHERE name = 'SuperStoreDB')
BEGIN
    ALTER DATABASE SuperStoreDB SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
    DROP DATABASE SuperStoreDB;
END
GO
--------------------------------------------------
-- CREATE DATABASE (if not exists)
--------------------------------------------------
IF NOT EXISTS (
    SELECT name 
    FROM sys.databases 
    WHERE name = 'SuperStoreDB'
)
BEGIN
    CREATE DATABASE SuperStoreDB;
END;
GO
--------------------------------------------------
-- USE DATABASE
--------------------------------------------------
USE SuperStoreDB;
GO

PRINT 'Building SuperStoreDB tables. Please wait.';

-- ============================================
-- 1. SUPPLIER TABLE
-- ============================================
CREATE TABLE SUPPLIER (
    SupID INT PRIMARY KEY,
    SupName VARCHAR(100) NOT NULL,
    TelNo VARCHAR(20)
);
GO

-- ============================================
-- 2. PRODUCT TABLE
-- ============================================
CREATE TABLE PRODUCT (
    Pid INT PRIMARY KEY,
    Pname VARCHAR(100) NOT NULL,
    PPrice DECIMAL(10,2) CHECK (PPrice >= 0)
);
GO

-- ============================================
-- 3. STOCK_IN_REC
-- ============================================
CREATE TABLE STOCK_IN_REC (
    StInvNo INT PRIMARY KEY,
    StInvDate DATE NOT NULL,
    TotalQtyPrice DECIMAL(12,2) DEFAULT 0,
    SupID INT NOT NULL,
    CONSTRAINT FK_StockRec_Supplier FOREIGN KEY (SupID) REFERENCES SUPPLIER(SupID)
);
GO

-- ============================================
-- 4. STOCK_IN_DETAIL (Stock Receive Details)
-- ============================================
CREATE TABLE STOCK_IN_DETAIL (
    StInvNo INT,
    Pid INT,
    QtyReceived INT NOT NULL CHECK (QtyReceived > 0),
    Price DECIMAL(10,2) NOT NULL CHECK (Price >= 0),
    Discount DECIMAL(4,2) DEFAULT 0 CHECK (Discount BETWEEN 0 AND 100),
    PRIMARY KEY (StInvNo, Pid),
    CONSTRAINT FK_StockDetail_Header FOREIGN KEY (StInvNo) REFERENCES STOCK_IN_REC(StInvNo) ON DELETE CASCADE,
    CONSTRAINT FK_StockDetail_Product FOREIGN KEY (Pid) REFERENCES PRODUCT(Pid)
);
GO

-- ============================================
-- 5. CUSTOMER TABLE
-- ============================================
CREATE TABLE CUSTOMER (
    Cid INT PRIMARY KEY,
    CName VARCHAR(100) NOT NULL,
    TelNo VARCHAR(20),
    Address VARCHAR(255)
);
GO

-- ============================================
-- 6. EMPLOYEE TABLE
-- ============================================
CREATE TABLE EMPLOYEE (
    EmpId INT PRIMARY KEY,
    EmpName VARCHAR(100) NOT NULL,
    City VARCHAR(50),
    Address VARCHAR(255)
);
GO

-- ============================================
-- 7. SHIPPER TABLE
-- ============================================
CREATE TABLE SHIPPER (
    ShpID INT PRIMARY KEY,
    ShpName VARCHAR(100) NOT NULL,
    Address VARCHAR(255),
    City VARCHAR(50),
    TelNo VARCHAR(20)
);
GO

-- ============================================
-- 8. INVOICE 
-- ============================================
CREATE TABLE INVOICE (
    InvNo INT PRIMARY KEY,
    InvDate DATE NOT NULL DEFAULT GETDATE(),
    TotalAmount DECIMAL(12,2) DEFAULT 0 CHECK (TotalAmount >= 0),
    Cid INT NOT NULL,
    EmpId INT NOT NULL,
    ShpID INT NOT NULL,
    CONSTRAINT FK_Order_Customer FOREIGN KEY (Cid) REFERENCES CUSTOMER(Cid),
    CONSTRAINT FK_Order_Employee FOREIGN KEY (EmpId) REFERENCES EMPLOYEE(EmpId),
    CONSTRAINT FK_Order_Shipper FOREIGN KEY (ShpID) REFERENCES SHIPPER(ShpID)
);
GO

-- ============================================
-- 9. ORDER_DETAIL (Junction Table between Invoice & Product)
-- ============================================
CREATE TABLE ORDER_DETAIL (
    InvNo INT,
    Pid INT,
    Qty INT NOT NULL CHECK (Qty > 0),
    Price DECIMAL(10,2) NOT NULL CHECK (Price >= 0),
    Discount DECIMAL(4,2) DEFAULT 0 CHECK (Discount BETWEEN 0 AND 100),
    PRIMARY KEY (InvNo, Pid),
    CONSTRAINT FK_OrderDetail_Order FOREIGN KEY (InvNo) REFERENCES INVOICE(InvNo) ON DELETE CASCADE,
    CONSTRAINT FK_OrderDetail_Product FOREIGN KEY (Pid) REFERENCES PRODUCT(Pid)
);
GO

PRINT 'Tables created successfully!';
GO

PRINT 'Inserting data into SuperStoreDB...';
GO

-- ============================================
-- 1. SUPPLIER DATA
-- ============================================
INSERT INTO SUPPLIER (SupID, SupName, TelNo) VALUES
(1, 'Dairy Farms Inc', '555-0101'),
(2, 'Beverage World', '555-0102'),
(3, 'Snack Masters', '555-0103'),
(4, 'Fresh Bakery Co', '555-0104'),
(5, 'Organic Farms', '555-0105'),
(6, 'Meat Suppliers Ltd', '555-0106'),
(7, 'Seafood Direct', '555-0107'),
(8, 'Frozen Foods Co', '555-0108'),
(9, 'Spice Kingdom', '555-0109'),
(10, 'Healthy Grains', '555-0110');
GO

-- ============================================
-- 2. PRODUCT DATA
-- ============================================
INSERT INTO PRODUCT (Pid, Pname, PPrice) VALUES
-- Dairy Products (101-110)
(101, 'Fresh Milk 1L', 3.99),
(102, 'Cheddar Cheese 500g', 5.99),
(103, 'Unsalted Butter 250g', 4.49),
(104, 'Greek Yogurt 500g', 4.99),
(105, 'Cream Cheese 200g', 3.49),
(106, 'Parmesan Cheese 200g', 6.99),
(107, 'Buttermilk 1L', 2.99),
(108, 'Sour Cream 400g', 3.29),
(109, 'Mozzarella Cheese 400g', 5.49),
(110, 'Almond Milk 1L', 4.99),

-- Beverages (201-215)
(201, 'Orange Juice 1L', 4.50),
(202, 'Cola 500ml', 1.99),
(203, 'Mineral Water 1L', 1.50),
(204, 'Apple Juice 1L', 4.00),
(205, 'Lemonade 1L', 3.50),
(206, 'Iced Tea 500ml', 2.29),
(207, 'Energy Drink 250ml', 3.99),
(208, 'Coconut Water 500ml', 3.49),
(209, 'Grape Juice 1L', 4.99),
(210, 'Ginger Ale 1L', 2.79),
(211, 'Tonic Water 1L', 2.49),
(212, 'Sports Drink 500ml', 2.99),
(213, 'Sparkling Water 1L', 2.29),
(214, 'Mango Juice 1L', 4.49),
(215, 'Pineapple Juice 1L', 4.29),

-- Snacks (301-315)
(301, 'Potato Chips 200g', 3.49),
(302, 'Chocolate Cookies 300g', 4.99),
(303, 'Mixed Nuts 400g', 7.99),
(304, 'Pretzels 250g', 2.99),
(305, 'Popcorn 150g', 2.49),
(306, 'Rice Cakes 100g', 1.99),
(307, 'Granola Bars 6pk', 4.49),
(308, 'Tortilla Chips 250g', 3.79),
(309, 'Salsa Dip 300g', 3.29),
(310, 'Hummus 200g', 3.99),
(311, 'Dried Fruits 200g', 5.49),
(312, 'Chocolate Bar 100g', 2.29),
(313, 'Gummy Bears 200g', 2.99),
(314, 'Trail Mix 300g', 5.99),
(315, 'Potato Sticks 150g', 2.29),

-- Bakery (401-412)
(401, 'White Bread 500g', 2.99),
(402, 'Chocolate Cake', 12.99),
(403, 'Croissant 6pcs', 5.99),
(404, 'Whole Wheat Bread 500g', 3.49),
(405, 'Bagels 6pcs', 4.99),
(406, 'Muffins 4pcs', 5.49),
(407, 'Danish Pastry 4pcs', 6.99),
(408, 'Sourdough Bread', 4.99),
(409, 'Cinnamon Rolls 4pcs', 5.99),
(410, 'Pita Bread 6pcs', 3.49),
(411, 'Garlic Bread', 3.99),
(412, 'Donuts 6pcs', 4.99),

-- Produce (501-515)
(501, 'Fresh Apples 1kg', 5.99),
(502, 'Bananas 1kg', 3.49),
(503, 'Oranges 1kg', 4.99),
(504, 'Strawberries 500g', 6.99),
(505, 'Grapes 500g', 5.49),
(506, 'Tomatoes 1kg', 4.49),
(507, 'Potatoes 2kg', 3.99),
(508, 'Onions 1kg', 2.49),
(509, 'Carrots 1kg', 2.99),
(510, 'Broccoli 500g', 3.49),
(511, 'Spinach 200g', 2.99),
(512, 'Lettuce 250g', 2.29),
(513, 'Cucumber 1pc', 1.49),
(514, 'Bell Peppers 3pcs', 4.49),
(515, 'Avocado 1pc', 2.49),

-- Meat (601-612)
(601, 'Chicken Breast 1kg', 9.99),
(602, 'Beef Steak 500g', 14.99),
(603, 'Pork Chops 500g', 8.99),
(604, 'Ground Beef 500g', 7.99),
(605, 'Chicken Thighs 1kg', 8.99),
(606, 'Lamb Chops 500g', 18.99),
(607, 'Turkey Breast 500g', 10.99),
(608, 'Bacon 200g', 5.99),
(609, 'Sausages 400g', 6.49),
(610, 'Salmon Fillet 400g', 15.99),
(611, 'Shrimp 400g', 12.99),
(612, 'Tuna Steak 400g', 13.99);
GO

-- ============================================
-- 3. STOCK_IN_REC DATA (Purchase Orders)
-- ============================================
INSERT INTO STOCK_IN_REC (StInvNo, StInvDate, TotalQtyPrice, SupID) VALUES
(1001, '2026-04-01', 0, 1),
(1002, '2026-04-02', 0, 2),
(1003, '2026-04-03', 0, 3),
(1004, '2026-04-04', 0, 4),
(1005, '2026-04-05', 0, 5),
(1006, '2026-04-06', 0, 6),
(1007, '2026-04-07', 0, 7),
(1008, '2026-04-08', 0, 1),
(1009, '2026-04-09', 0, 2),
(1010, '2026-04-10', 0, 3),
(1011, '2026-04-11', 0, 8),
(1012, '2026-04-12', 0, 9),
(1013, '2026-04-13', 0, 10),
(1014, '2026-04-14', 0, 4),
(1015, '2026-04-15', 0, 5);
GO

-- ============================================
-- 4. STOCK_IN_DETAIL DATA
-- ============================================
INSERT INTO STOCK_IN_DETAIL (StInvNo, Pid, QtyReceived, Price, Discount) VALUES
-- Stock Receive 1001 (Dairy Farms Inc)
(1001, 101, 150, 2.50, 0),
(1001, 102, 80, 4.00, 5),
(1001, 103, 100, 3.00, 0),
(1001, 104, 120, 3.20, 0),
(1001, 105, 90, 2.20, 0),
(1001, 106, 60, 4.50, 0),
(1001, 107, 100, 1.80, 0),
(1001, 108, 80, 2.10, 0),
(1001, 109, 70, 3.80, 0),
(1001, 110, 90, 3.20, 0),

-- Stock Receive 1002 (Beverage World)
(1002, 201, 120, 3.00, 0),
(1002, 202, 300, 1.20, 10),
(1002, 203, 250, 0.90, 0),
(1002, 204, 100, 2.80, 0),
(1002, 205, 90, 2.40, 0),
(1002, 206, 150, 1.50, 0),
(1002, 207, 120, 2.50, 5),
(1002, 208, 100, 2.20, 0),
(1002, 209, 80, 3.20, 0),
(1002, 210, 120, 1.80, 0),
(1002, 211, 100, 1.60, 0),
(1002, 212, 150, 1.90, 0),
(1002, 213, 120, 1.40, 0),
(1002, 214, 90, 3.00, 0),
(1002, 215, 85, 2.90, 0),

-- Stock Receive 1003 (Snack Masters)
(1003, 301, 200, 2.20, 0),
(1003, 302, 150, 3.50, 5),
(1003, 303, 100, 5.50, 0),
(1003, 304, 180, 1.80, 0),
(1003, 305, 200, 1.60, 0),
(1003, 306, 250, 1.20, 0),
(1003, 307, 120, 3.00, 0),
(1003, 308, 150, 2.40, 0),
(1003, 309, 130, 2.20, 0),
(1003, 310, 100, 2.60, 0),
(1003, 311, 80, 3.80, 0),
(1003, 312, 200, 1.40, 0),
(1003, 313, 180, 1.90, 0),
(1003, 314, 90, 4.00, 0),
(1003, 315, 160, 1.50, 0),

-- Stock Receive 1004 (Fresh Bakery Co)
(1004, 401, 120, 1.80, 0),
(1004, 402, 60, 8.00, 0),
(1004, 403, 100, 3.50, 0),
(1004, 404, 90, 2.20, 0),
(1004, 405, 80, 3.20, 0),
(1004, 406, 70, 3.80, 0),
(1004, 407, 60, 4.50, 0),
(1004, 408, 85, 3.00, 0),
(1004, 409, 75, 3.80, 0),
(1004, 410, 100, 2.20, 0),
(1004, 411, 90, 2.50, 0),
(1004, 412, 80, 3.20, 0),

-- Stock Receive 1005 (Organic Farms)
(1005, 501, 100, 4.00, 0),
(1005, 502, 120, 2.20, 0),
(1005, 503, 110, 3.20, 0),
(1005, 504, 80, 4.50, 0),
(1005, 505, 90, 3.80, 0),
(1005, 506, 100, 2.80, 0),
(1005, 507, 150, 2.20, 0),
(1005, 508, 120, 1.60, 0),
(1005, 509, 100, 1.90, 0),
(1005, 510, 90, 2.30, 0),
(1005, 511, 120, 1.80, 0),
(1005, 512, 100, 1.40, 0),
(1005, 513, 150, 0.90, 0),
(1005, 514, 80, 2.80, 0),
(1005, 515, 100, 1.60, 0),

-- Stock Receive 1006 (Meat Suppliers Ltd)
(1006, 601, 80, 7.00, 0),
(1006, 602, 60, 11.00, 0),
(1006, 603, 70, 6.50, 0),
(1006, 604, 90, 5.50, 0),
(1006, 605, 75, 6.20, 0),
(1006, 606, 40, 13.50, 0),
(1006, 607, 50, 7.50, 0),
(1006, 608, 100, 4.00, 0),
(1006, 609, 80, 4.50, 0),

-- Stock Receive 1007 (Seafood Direct)
(1007, 610, 50, 11.00, 0),
(1007, 611, 60, 9.00, 0),
(1007, 612, 45, 10.00, 0),

-- Stock Receive 1008 (Dairy Farms Inc - second order)
(1008, 101, 120, 2.45, 0),
(1008, 102, 60, 3.90, 0),
(1008, 104, 80, 3.10, 0),
(1008, 105, 70, 2.10, 0),
(1008, 109, 50, 3.70, 0),

-- Stock Receive 1009 (Beverage World - second order)
(1009, 201, 90, 2.90, 5),
(1009, 202, 200, 1.15, 0),
(1009, 203, 150, 0.85, 0),
(1009, 204, 80, 2.70, 0),
(1009, 206, 100, 1.45, 0),

-- Stock Receive 1010 (Snack Masters - second order)
(1010, 301, 120, 2.15, 0),
(1010, 302, 100, 3.40, 0),
(1010, 303, 70, 5.40, 0),
(1010, 304, 100, 1.75, 0),
(1010, 305, 120, 1.55, 0),

-- Stock Receive 1011 (Frozen Foods Co)
(1011, 301, 100, 2.10, 0),
(1011, 302, 80, 3.30, 0),
(1011, 305, 120, 1.50, 0),

-- Stock Receive 1012 (Spice Kingdom)
(1012, 309, 100, 2.10, 0),
(1012, 310, 80, 2.50, 0),

-- Stock Receive 1013 (Healthy Grains)
(1013, 401, 100, 1.70, 0),
(1013, 404, 80, 2.10, 0),
(1013, 405, 70, 3.00, 0),

-- Stock Receive 1014 (Fresh Bakery Co - second order)
(1014, 402, 40, 7.80, 0),
(1014, 403, 60, 3.40, 0),
(1014, 408, 50, 2.90, 0),

-- Stock Receive 1015 (Organic Farms - second order)
(1015, 501, 70, 3.90, 0),
(1015, 502, 80, 2.15, 0),
(1015, 503, 60, 3.10, 0),
(1015, 504, 50, 4.40, 0);
GO

-- ============================================
-- 5. CUSTOMER DATA
-- ============================================
INSERT INTO CUSTOMER (Cid, CName, TelNo, Address) VALUES
(101, 'John Doe', '555-1001', '123 Main St, New York, NY 10001'),
(102, 'Jane Smith', '555-1002', '456 Oak Ave, Los Angeles, CA 90001'),
(103, 'Bob Williams', '555-1003', '789 Pine Rd, Chicago, IL 60601'),
(104, 'Alice Brown', '555-1004', '321 Elm St, Houston, TX 77001'),
(105, 'Charlie Davis', '555-1005', '654 Maple Dr, Phoenix, AZ 85001'),
(106, 'Diana Evans', '555-1006', '987 Cedar Ln, Philadelphia, PA 19101'),
(107, 'Edward Frank', '555-1007', '147 Birch Blvd, San Antonio, TX 78201'),
(108, 'Fiona Green', '555-1008', '258 Walnut Way, San Diego, CA 92101'),
(109, 'George Harris', '555-1009', '369 Spruce St, Dallas, TX 75201'),
(110, 'Helen Clark', '555-1010', '741 Ash Ave, San Jose, CA 95101'),
(111, 'Ian Wilson', '555-1011', '852 Fir Ln, Austin, TX 73301'),
(112, 'Julia Martinez', '555-1012', '963 Palm Blvd, Fort Worth, TX 76101'),
(113, 'Kevin Taylor', '555-1013', '159 Willow Way, Charlotte, NC 28201'),
(114, 'Laura Anderson', '555-1014', '753 Poplar St, Detroit, MI 48201'),
(115, 'Michael Thomas', '555-1015', '357 Cypress Ave, Boston, MA 02101');
GO

-- ============================================
-- 6. EMPLOYEE DATA
-- ============================================
INSERT INTO EMPLOYEE (EmpId, EmpName, City, Address) VALUES
(201, 'Mike Ross', 'New York', '123 Sales St, New York, NY'),
(202, 'Rachel Zane', 'Los Angeles', '456 Legal Ave, Los Angeles, CA'),
(203, 'Harvey Specter', 'Chicago', '789 Law Rd, Chicago, IL'),
(204, 'Donna Paulsen', 'Houston', '321 Admin Blvd, Houston, TX'),
(205, 'Louis Litt', 'Phoenix', '654 Finance Ln, Phoenix, AZ'),
(206, 'Jessica Pearson', 'New York', '987 Partner Way, New York, NY'),
(207, 'Katrina Bennett', 'San Diego', '147 Associate Dr, San Diego, CA'),
(208, 'Alex Williams', 'Dallas', '258 Sales Rd, Dallas, TX'),
(209, 'Sarah Chen', 'San Jose', '369 Support Ln, San Jose, CA'),
(210, 'David Kim', 'Austin', '741 Service Ave, Austin, TX');
GO

-- ============================================
-- 7. SHIPPER DATA
-- ============================================
INSERT INTO SHIPPER (ShpID, ShpName, Address, City, TelNo) VALUES
(301, 'FedEx', '1000 FedEx Way', 'Memphis, TN', '1-800-463-3339'),
(302, 'UPS', '55 Glenlake Pkwy', 'Atlanta, GA', '1-800-742-5877'),
(303, 'DHL', '1200 S Pine Island Rd', 'Plantation, FL', '1-800-225-5345'),
(304, 'USPS', '475 LEnfant Plaza SW', 'Washington, DC', '1-800-275-8777'),
(305, 'Amazon Logistics', '410 Terry Ave N', 'Seattle, WA', '1-888-280-4331'),
(306, 'Ontrac', '4550 West 160th St', 'Cleveland, OH', '1-800-334-5000');
GO

-- ============================================
-- 8. INVOICE DATA
-- ============================================
INSERT INTO INVOICE (InvNo, InvDate, TotalAmount, Cid, EmpId, ShpID) VALUES
(1, '2026-04-01', 0, 101, 201, 301),
(2, '2026-04-02', 0, 102, 202, 302),
(3, '2026-04-03', 0, 103, 201, 301),
(4, '2026-04-04', 0, 101, 203, 303),
(5, '2026-04-05', 0, 104, 202, 302),
(6, '2026-04-06', 0, 105, 204, 304),
(7, '2026-04-07', 0, 102, 201, 301),
(8, '2026-04-08', 0, 103, 202, 302),
(9, '2026-04-09', 0, 106, 205, 303),
(10, '2026-04-10', 0, 107, 206, 305),
(11, '2026-04-11', 0, 108, 207, 301),
(12, '2026-04-12', 0, 109, 201, 302),
(13, '2026-04-13', 0, 110, 202, 303),
(14, '2026-04-14', 0, 111, 203, 304),
(15, '2026-04-15', 0, 102, 204, 305),
(16, '2026-04-16', 0, 112, 205, 306),
(17, '2026-04-17', 0, 113, 206, 301),
(18, '2026-04-18', 0, 114, 207, 302),
(19, '2026-04-19', 0, 115, 208, 303),
(20, '2026-04-20', 0, 101, 209, 304),
(21, '2026-04-21', 0, 102, 210, 305),
(22, '2026-04-22', 0, 103, 201, 306),
(23, '2026-04-23', 0, 104, 202, 301),
(24, '2026-04-24', 0, 105, 203, 302),
(25, '2026-04-25', 0, 106, 204, 303);
GO

-- ============================================
-- 9. ORDER_DETAIL DATA
-- ============================================
INSERT INTO ORDER_DETAIL (InvNo, Pid, Qty, Price, Discount) VALUES
-- Invoice 1
(1, 101, 2, 3.99, 0),
(1, 102, 1, 5.99, 0),
(1, 401, 1, 2.99, 0),

-- Invoice 2
(2, 201, 3, 4.50, 0),
(2, 202, 5, 1.99, 0),
(2, 303, 2, 7.99, 0),

-- Invoice 3
(3, 301, 2, 3.49, 0),
(3, 302, 1, 4.99, 0),
(3, 401, 2, 2.99, 10),

-- Invoice 4
(4, 601, 3, 9.99, 5),
(4, 602, 2, 14.99, 0),
(4, 501, 2, 5.99, 0),

-- Invoice 5
(5, 501, 2, 5.99, 0),
(5, 502, 2, 3.49, 0),

-- Invoice 6
(6, 101, 1, 3.99, 0),
(6, 103, 2, 4.49, 0),
(6, 104, 3, 4.99, 0),

-- Invoice 7
(7, 402, 2, 12.99, 0),
(7, 401, 3, 2.99, 0),
(7, 403, 2, 5.99, 0),

-- Invoice 8
(8, 202, 4, 1.99, 0),
(8, 203, 3, 1.50, 0),

-- Invoice 9
(9, 602, 3, 14.99, 0),
(9, 601, 2, 9.99, 0),
(9, 603, 2, 8.99, 0),

-- Invoice 10
(10, 301, 3, 3.49, 0),
(10, 304, 5, 2.99, 0),
(10, 501, 2, 5.99, 0),

-- Invoice 11
(11, 102, 2, 5.99, 0),
(11, 104, 3, 4.99, 0),
(11, 201, 2, 4.50, 0),

-- Invoice 12
(12, 303, 1, 7.99, 0),
(12, 402, 1, 12.99, 0),
(12, 501, 2, 5.99, 0),

-- Invoice 13
(13, 601, 4, 9.99, 0),
(13, 602, 2, 14.99, 0),
(13, 101, 2, 3.99, 0),

-- Invoice 14
(14, 202, 5, 1.99, 0),
(14, 203, 4, 1.50, 0),
(14, 301, 2, 3.49, 0),

-- Invoice 15
(15, 402, 1, 12.99, 0),
(15, 301, 4, 3.49, 0),
(15, 501, 3, 5.99, 0),

-- Invoice 16
(16, 105, 2, 3.49, 0),
(16, 106, 1, 6.99, 0),
(16, 204, 2, 4.00, 0),

-- Invoice 17
(17, 302, 3, 4.99, 0),
(17, 304, 2, 2.99, 0),
(17, 405, 1, 4.99, 0),

-- Invoice 18
(18, 502, 4, 3.49, 0),
(18, 503, 2, 4.99, 0),
(18, 604, 2, 7.99, 0),

-- Invoice 19
(19, 107, 3, 2.99, 0),
(19, 108, 2, 3.29, 0),
(19, 205, 2, 3.50, 0),

-- Invoice 20
(20, 109, 2, 5.49, 0),
(20, 110, 1, 4.99, 0),
(20, 206, 3, 2.29, 0),

-- Invoice 21
(21, 207, 2, 3.99, 0),
(21, 208, 3, 3.49, 0),
(21, 305, 4, 2.49, 0),

-- Invoice 22
(22, 209, 1, 4.99, 0),
(22, 210, 2, 2.79, 0),
(22, 306, 3, 1.99, 0),

-- Invoice 23
(23, 211, 2, 2.49, 0),
(23, 212, 3, 2.99, 0),
(23, 307, 2, 4.49, 0),

-- Invoice 24
(24, 213, 4, 2.29, 0),
(24, 214, 2, 4.49, 0),
(24, 308, 3, 3.79, 0),

-- Invoice 25
(25, 215, 2, 4.29, 0),
(25, 309, 2, 3.29, 0),
(25, 310, 1, 3.99, 0);
GO

-- ============================================
-- UPDATE CALCULATED FIELDS
-- ============================================

-- Update TotalAmount in INVOICE
UPDATE INVOICE 
SET TotalAmount = (
    SELECT ISNULL(SUM(od.Qty * od.Price * (1 - od.Discount/100)), 0)
    FROM ORDER_DETAIL od
    WHERE od.InvNo = INVOICE.InvNo
);
GO

-- Update TotalQtyPrice in STOCK_IN_REC
UPDATE STOCK_IN_REC 
SET TotalQtyPrice = (
    SELECT ISNULL(SUM(sd.QtyReceived * sd.Price * (1 - sd.Discount/100)), 0)
    FROM STOCK_IN_DETAIL sd
    WHERE sd.StInvNo = STOCK_IN_REC.StInvNo
);
GO

SELECT * FROM ORDER_DETAIL
SELECT * FROM INVOICE
SELECT * FROM STOCK_IN_DETAIL
SELECT * FROM STOCK_IN_REC
SELECT * FROM PRODUCT
SELECT * FROM SUPPLIER
SELECT * FROM CUSTOMER
SELECT * FROM EMPLOYEE
SELECT * FROM SHIPPER