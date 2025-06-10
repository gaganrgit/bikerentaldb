<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = openConnection();

if (isset($_GET['rental_id']) && isset($_GET['action'])) {
    $rental_id = $_GET['rental_id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        // Update rental status to 'Ongoing' and assign admin_id
        $updateQuery = "UPDATE Rentals SET status = 'Ongoing', admin_id = ? WHERE rental_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ii", $_SESSION['admin_id'], $rental_id);
        $stmt->execute();
        $stmt->close();

        // Insert a new record into Payments table
        $insertPayment = "INSERT INTO Payments (amount, payment_status, payment_method) VALUES (0, 'Pending', 'Not Set')";
        $stmt = $conn->prepare($insertPayment);
        $stmt->execute();
        $payment_id = $stmt->insert_id; // Get last inserted payment ID
        $stmt->close();

        // Update Rentals table with the payment_id
        $updateRentalPayment = "UPDATE Rentals SET payment_id = ? WHERE rental_id = ?";
        $stmt = $conn->prepare($updateRentalPayment);
        $stmt->bind_param("ii", $payment_id, $rental_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = "Rental Approved Successfully!";
    } elseif ($action == 'reject') {
        // If rejected, update status to 'Rejected'
        $updateQuery = "UPDATE Rentals SET status = 'Rejected' WHERE rental_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = "Rental Rejected!";
    }
}

closeConnection($conn);
header("Location: manage_rentals.php");
exit();
?>
