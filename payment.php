<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

if (!isset($_POST['rental_id'])) {
    header("Location: my_rentals.php");
    exit();
}

$conn = openConnection();
$rental_id = $_POST['rental_id'];

// Fetch rental details
$query = "SELECT r.total_cost, b.model, b.brand 
          FROM Rentals r
          JOIN AssignedTo a ON r.rental_id = a.rental_id
          JOIN Bikes b ON a.bike_id = b.bike_id
          WHERE r.rental_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rental_id);
$stmt->execute();
$result = $stmt->get_result();
$rental = $result->fetch_assoc();

if (!$rental) {
    header("Location: my_rentals.php");
    exit();
}

closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('payment-bg.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
        }
        .container {
            width: 40%;
            margin: auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            margin-top: 50px;
        }
        h1 {
            color: #ffcc00;
        }
        label, select, input {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .btn {
            background: #ffcc00;
            color: black;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover {
            background: #e6b800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment for Rental ID #<?php echo $rental_id; ?></h1>
        <p>Bike: <?php echo $rental['brand'] . " " . $rental['model']; ?></p>
        <p>Total Cost: ₹<?php echo $rental['total_cost']; ?></p>

        <form action="process_payment.php" method="POST">
            <input type="hidden" name="rental_id" value="<?php echo $rental_id; ?>">
            <input type="hidden" name="amount" value="<?php echo $rental['total_cost']; ?>">
            
            <label for="payment_method">Choose Payment Method:</label>
            <select name="payment_method" required>
                <option value="Online">Online</option>
                <option value="Offline">Offline</option>
            </select>

            <button type="submit" class="btn">Pay Now</button>
        </form>
    </div>
</body>
</html>
