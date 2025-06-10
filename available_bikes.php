<?php
session_start();
include 'db_connection.php';

$conn = openConnection();

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

// Fetch available bikes
$query = "SELECT * FROM Bikes WHERE availability = 1";
$result = $conn->query($query);

closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Bikes</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1598535840442-4b90a21b1127?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDl8fHxlbnwwfHx8fHw%3D') no-repeat center center/cover;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Available Bikes for Rent</h1>
        <table>
            <tr>
                <th>Model</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Rent Per Hour</th>
                <th>Availability</th>
                <th>Book</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['model']; ?></td>
                    <td><?php echo $row['brand']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td>₹<?php echo $row['rent_per_hour']; ?></td>
                    <td><?php echo $row['availability'] ? "Available" : "Not Available"; ?></td>
                    <td>
                        <button class="btn" onclick="openBookingForm(<?php echo $row['bike_id']; ?>)">Book</button>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <h2>Costliest Rented Bike Amount is : ₹ 200.00<h2>
    </div>

    <!-- Booking Form Modal -->
    <div id="bookingForm" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:white; padding:20px; color:black; border-radius:8px;">
        <h2>Book Bike</h2>
        <form method="POST" action="process_booking.php">
            <input type="hidden" name="bike_id" id="bike_id">
            <label>Pickup Date:</label>
            <input type="date" name="pickup_date" id="pickup_date" required><br><br>
            <label>Drop Date:</label>
            <input type="date" name="drop_date" id="drop_date" required><br><br>
            <button type="submit" class="btn">Submit Booking</button>
            <button type="button" class="btn" onclick="document.getElementById('bookingForm').style.display='none'">Cancel</button>
        </form>
    </div>

    <script>
        function openBookingForm(bikeId) {
            document.getElementById("bike_id").value = bikeId;
            document.getElementById("bookingForm").style.display = "block";
        }

        document.addEventListener("DOMContentLoaded", function () {
            let today = new Date().toISOString().split("T")[0];
            document.getElementById("pickup_date").setAttribute("min", today);
            document.getElementById("drop_date").setAttribute("min", today);

            document.getElementById("pickup_date").addEventListener("change", function () {
                document.getElementById("drop_date").setAttribute("min", this.value);
            });
        });
    </script>
</body>
</html>
