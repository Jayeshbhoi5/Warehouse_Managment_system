<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '@jayD004361', 'wms');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product-name'];
    $quantity = $_POST['quantity'];
    $cost = $_POST['cost'];
    $discount = $_POST['discount'];

    // Calculate total amount
    $total_amount = ($cost * $quantity) - (($cost * $quantity) * ($discount / 100));

    // Insert into database
    $sql = "INSERT INTO inventory (product_name, quantity, cost, discount, total_amount) VALUES ('$product_name', '$quantity', '$cost', '$discount', '$total_amount')";
    if ($conn->query($sql) === TRUE) {
        echo "Product added to inventory successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
