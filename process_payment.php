<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rental_id = $_POST['rental_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    $conn = openConnection();

    // Insert payment record
    $query = "INSERT INTO Payments (amount, payment_status, payment_method) VALUES (?, 'Completed', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ds", $amount, $payment_method);
    $stmt->execute();
    $payment_id = $stmt->insert_id;  // Get the last inserted payment ID

    // Update Rentals table with payment_id and mark as "Completed"
    $update_query = "UPDATE Rentals SET payment_id = ?, status = 'Completed' WHERE rental_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $payment_id, $rental_id);
    $update_stmt->execute();

    closeConnection($conn);

    // Store success message in session
    $_SESSION['payment_success'] = "Payment Successful! Thank you for renting with us.";
    
    header("Location: my_rentals.php");
    exit();
} else {
    header("Location: my_rentals.php");
    exit();
}
?>
