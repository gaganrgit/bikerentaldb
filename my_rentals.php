<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$conn = openConnection();
$customer_id = $_SESSION['customer_id'];

// Fetch customer's rental bookings
$query = "SELECT r.rental_id, b.model, b.brand, r.pickup_date, r.drop_date, r.total_cost, r.status, p.payment_status
          FROM Rentals r
          JOIN AssignedTo a ON r.rental_id = a.rental_id
          JOIN Bikes b ON a.bike_id = b.bike_id
          LEFT JOIN Payments p ON r.payment_id = p.payment_id
          WHERE r.customer_id = ?
          ORDER BY r.rental_id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
closeConnection($conn);

// Check if a payment success message exists in session
$payment_message = isset($_SESSION['payment_success']) ? $_SESSION['payment_success'] : "";
unset($_SESSION['payment_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rentals</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1591216105236-5ba45970702a?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDd8fHxlbnwwfHx8fHw%3D') no-repeat center center/cover;
            background-size: cover;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid white;
            text-align: center;
        }
        th {
            background: white;
            color: black;
            border-bottom: 3px solid black;
            font-weight: bold;
        }
        .btn {
            background: linear-gradient(45deg, #222, #000);
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 5px 10px rgba(255, 255, 255, 0.2);
        }
        .btn:hover {
            background: white;
            color: black;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
        }
        .pending {
            color: orange;
            font-weight: bold;
        }
        .ongoing {
            color: green;
            font-weight: bold;
        }
        .completed {
            color: green;
            font-weight: bold;
        }
        /* Popup styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            text-align: center;
            z-index: 1000;
        }
        .popup button {
            margin-top: 10px;
            padding: 8px 15px;
            background: white;
            color: black;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>My Rentals</h1>

        <table>
            <tr>
                <th>Rental ID</th>
                <th>Bike Model</th>
                <th>Brand</th>
                <th>Pickup Date</th>
                <th>Drop Date</th>
                <th>Total Cost</th>
                <th>Status</th>
                <th>Payment</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['rental_id']; ?></td>
                    <td><?php echo $row['model']; ?></td>
                    <td><?php echo $row['brand']; ?></td>
                    <td><?php echo $row['pickup_date']; ?></td>
                    <td><?php echo $row['drop_date']; ?></td>
                    <td>₹<?php echo $row['total_cost']; ?></td>
                    <td class="<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <span class="pending">Waiting for Approval</span>
                        <?php } elseif ($row['status'] == 'Ongoing' && $row['payment_status'] == 'Pending') { ?>
                            <form action="payment.php" method="POST">
                                <input type="hidden" name="rental_id" value="<?php echo $row['rental_id']; ?>">
                                <button type="submit" class="btn">Proceed to Payment</button>
                            </form>
                        <?php } elseif ($row['payment_status'] == 'Completed') { ?>
                            <span class="completed">Paid</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Popup message -->
    <?php if ($payment_message) { ?>
        <div id="popup" class="popup">
            <p><?php echo $payment_message; ?></p>
            <button onclick="closePopup()">OK</button>
        </div>
    <?php } ?>

    <script>
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        // Show popup if payment was successful
        window.onload = function() {
            var popup = document.getElementById('popup');
            if (popup) {
                popup.style.display = 'block';
            }
        };
    </script>

</body>
</html>
