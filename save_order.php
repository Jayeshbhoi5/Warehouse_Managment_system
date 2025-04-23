<?php
session_start();
if (!isset($_SESSION['username'])) {
    die('Not authorized');
}

$conn = new mysqli('localhost', 'root', '@jayD004361', 'wms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert main order
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, order_date, sub_total, discount_percent, discount_amount, net_total) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdddd", 
            $data['customerName'],
            $data['orderDate'],
            $data['subTotal'],
            $data['discountPercent'],
            $data['discountAmount'],
            $data['netTotal']
        );
        $stmt->execute();
        $orderId = $conn->insert_id;
        
        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
        foreach ($data['items'] as $item) {
            $stmt->bind_param("isids",
                $orderId,
                $item['productName'],
                $item['quantity'],
                $item['price'],
                $item['total']
            );
            $stmt->execute();
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'orderId' => $orderId]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}