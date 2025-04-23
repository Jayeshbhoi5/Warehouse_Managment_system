<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$conn = new mysqli('localhost', 'root', '@jayD004361', 'wms');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$stmt = $conn->prepare("INSERT INTO inventory (product_name, quantity, cost, description) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sids", 
    $data['productName'],
    $data['quantity'],
    $data['cost'],
    $data['description']
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>