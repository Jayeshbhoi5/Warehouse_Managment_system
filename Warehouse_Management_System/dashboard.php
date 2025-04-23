<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '@jayD004361', 'wms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total products count
$result = $conn->query("SELECT COUNT(*) as total FROM products");
$totalProducts = $result->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management System</title>
    <link rel="stylesheet" href="st.css">
    <!-- Chart.js for graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar">
        <div class="navbar-left">
            <button id="menuToggle" onclick="toggleMenu()">☰</button>
            <h1>Warehouse Management</h1>
        </div>
        <div class="navbar-right">
            <span><?php echo $_SESSION['username']; ?></span>
 <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </nav>

    <!-- Side Menu -->
    <div id="sideMenu" class="side-menu">
        <a href="#" onclick="showSection('dashboard')" class="active">WMS Dashboard</a>
        <a href="#" onclick="showSection('inventory')">Inventory Management</a>
        <a href="#" onclick="showSection('about')">About Us</a>
        <a href="#" onclick="showSection('contact')">Contact Us</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Section -->
        <section id="dashboardSection" class="content-section">
            <h2>WMS Dashboard</h2>
            <div class="charts-container">
                <div class="chart-box">
                    <h3>Inventory Levels</h3>
                    <canvas id="inventoryChart"></canvas>
                </div>
                <div class="chart-box">
                    <h3>Order Trends</h3>
                    <canvas id="orderChart"></canvas>
                </div>
            </div>
            <div class="stats-container">
    <div class="row">
        <div class="stat-box">
            <h3>Total Products</h3>
            <p><?php echo $totalProducts; ?></p>
        </div>
        <div class="stat-box">
            <h3>Active Orders</h3>
            <p>15</p>
        </div>
    </div>
    <div class="row">
        <div class="stat-box">
            <h3>Low Stock</h3>
            <p>3</p>
        </div>
        <div class="stat-box">
            <h3>Total Revenue</h3>
            <p>$45,000</p>
        </div>
    </div>
</div>
        </section>

        <!-- Inventory Section -->
<!-- Update the inventory section in dashboard.php -->
<section id="inventorySection" class="content-section" style="display: none;">
    <h2>Inventory Management</h2>
    
    <div class="order-form-container">
        <h3>Make a Order List</h3>
        <div class="order-header">
            <div class="order-date">
                <label>Order Date:</label>
                <input type="date" id="orderDate" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="customer-name">
                <label>Customer Name*:</label>
                <input type="text" id="customerName" required>
            </div>
        </div>

        <div class="order-items-table">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="orderItems">
                    <tr>
                        <td>1</td>
                        <td><input type="text" class="product-name" placeholder="Enter product name"></td>
                        <td><input type="number" class="order-qty" onchange="calculateItemTotal(this)" min="1"></td>
                        <td><input type="number" class="price" onchange="calculateItemTotal(this)"></td>
                        <td><input type="text" class="item-total" readonly></td>
                    </tr>
                </tbody>
            </table>
            <div class="order-buttons">
                <button onclick="addOrderRow()" class="add-btn">Add Item</button>
                <button onclick="removeOrderRow()" class="remove-btn">Remove Item</button>
            </div>
        </div>

        <div class="order-summary">
            <div class="summary-item">
                <label>Sub Total:</label>
                <input type="text" id="subTotal" readonly>
            </div>
            <div class="summary-item">
                <label>Discount (%):</label>
                <input type="number" id="discountPercent" onchange="calculateNetTotal()" min="0" max="100">
            </div>
            <div class="summary-item">
                <label>Discount Amount:</label>
                <input type="text" id="discountAmount" readonly>
            </div>
            <div class="summary-item">
                <label>Net Total:</label>
                <input type="text" id="netTotal" readonly>
            </div>
        </div>
        
        <!-- Add the Place Order button -->
        <div class="order-submit">
            <button onclick="placeOrder()" class="place-order-btn">Place Order</button>
        </div>
    </div>
</section>
   

<!-- Update About Section -->
<section id="aboutSection" class="content-section white-box" style="display: none;">
    <h2>About Us</h2>
    <div class="content-box">
        <p>We are a leading provider of warehouse management solutions, helping businesses 
           optimize their operations and improve efficiency.</p>
    </div>
</section>

<!-- Update Contact Section -->
<section id="contactSection" class="content-section white-box" style="display: none;">
    <h2>Contact Us</h2>
    <div class="content-box">
        <p>Email: support@warehouse.com</p>
        <p>Phone: +1 234 567 890</p>
        <p>Address: 123 Warehouse Street, Business District</p>
    </div>
</section>
    </div>

    <script src="script1.js"></script>
    <!-- Add this right before the closing </body> tag in dashboard.php -->
    
    <!-- Footer Section -->


    <script src="script1.js"></script>
</body>
</html>
</body>
</html>