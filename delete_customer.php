<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['customer_id'])) {
    $conn = openConnection();
    
    $customer_id = $_GET['customer_id'];
    $query = "DELETE FROM Customer WHERE customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);

    if ($stmt->execute()) {
        header("Location: manage_customers.php");
        exit();
    }

    closeConnection($conn);
}
?>
