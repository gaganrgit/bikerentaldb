<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$conn = openConnection();
$customer_id = $_SESSION['customer_id'];
$bike_id = $_POST['bike_id'];
$pickup_date = $_POST['pickup_date'];
$drop_date = $_POST['drop_date'];

// Validate inputs
if (empty($pickup_date) || empty($drop_date)) {
    echo "<script>alert('Please enter valid dates!'); window.history.back();</script>";
    exit();
}

if ($drop_date <= $pickup_date) {
    echo "<script>alert('Drop date must be after pickup date!'); window.history.back();</script>";
    exit();
}

// Get bike rent per hour
$bike_query = "SELECT rent_per_hour FROM Bikes WHERE bike_id = ?";
$stmt = $conn->prepare($bike_query);
$stmt->bind_param("i", $bike_id);
$stmt->execute();
$result = $stmt->get_result();
$bike = $result->fetch_assoc();
$rent_per_hour = $bike['rent_per_hour'];

// Calculate total cost
$pickup_datetime = new DateTime($pickup_date);
$drop_datetime = new DateTime($drop_date);
$interval = $pickup_datetime->diff($drop_datetime);
$total_hours = $interval->days * 24;  // Convert days to hours
$total_cost = $total_hours * $rent_per_hour;

// Insert booking into Rentals table
$insert_query = "INSERT INTO Rentals (customer_id, pickup_date, drop_date, total_cost, status) 
                 VALUES (?, ?, ?, ?, 'Pending')";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("issi", $customer_id, $pickup_date, $drop_date, $total_cost);

if ($stmt->execute()) {
    $rental_id = $stmt->insert_id;

    // Link bike to rental
    $assign_query = "INSERT INTO AssignedTo (rental_id, bike_id) VALUES (?, ?)";
    $stmt = $conn->prepare($assign_query);
    $stmt->bind_param("ii", $rental_id, $bike_id);
    $stmt->execute();

    echo "<script>alert('Booking submitted! Waiting for admin approval.'); window.location='my_rentals.php';</script>";
} else {
    echo "<script>alert('Booking failed! Try again.'); window.history.back();</script>";
}

closeConnection($conn);
?>
