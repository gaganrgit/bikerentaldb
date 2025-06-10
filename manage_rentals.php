<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = openConnection();

// Fetch all pending rentals
$query = "SELECT r.rental_id, c.name AS customer_name, b.model, b.brand, r.pickup_date, r.drop_date, r.status 
          FROM Rentals r 
          JOIN Customer c ON r.customer_id = c.customer_id
          JOIN AssignedTo at ON r.rental_id = at.rental_id
          JOIN Bikes b ON at.bike_id = b.bike_id
          WHERE r.status = 'Pending'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rentals</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1706624152368-78fdf877f327?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDd8fHxlbnwwfHx8fHw%3D') no-repeat center center/cover;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: rgba(0, 0, 0, 0.85);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(255, 255, 255, 0.2);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            background: white;
            color: black;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid black;
            text-align: center;
        }

        th {
            background: black;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        tr:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 8px 12px;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            display: inline-block;
        }

        .approve {
            background: green;
        }

        .reject {
            background: red;
        }

        .approve:hover {
            background: darkgreen;
        }

        .reject:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Rentals</h2>
        <table>
            <tr>
                <th>Rental ID</th>
                <th>Customer Name</th>
                <th>Bike Model</th>
                <th>Brand</th>
                <th>Pickup Date</th>
                <th>Drop Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['rental_id'] ?></td>
                    <td><?= $row['customer_name'] ?></td>
                    <td><?= $row['model'] ?></td>
                    <td><?= $row['brand'] ?></td>
                    <td><?= $row['pickup_date'] ?></td>
                    <td><?= $row['drop_date'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <a href="process_rental.php?rental_id=<?= $row['rental_id'] ?>&action=approve" class="btn approve">Approve</a>
                        <a href="process_rental.php?rental_id=<?= $row['rental_id'] ?>&action=reject" class="btn reject">Reject</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

<?php closeConnection($conn); ?>
