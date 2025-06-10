<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = openConnection();

// Fetch all bikes
$query = "SELECT * FROM Bikes";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bikes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1666929436278-bc660d25825d?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDU4fHx8ZW58MHx8fHx8') no-repeat center center/cover;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto 0;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: black;
            color: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid white;
            text-align: center;
        }
        th {
            background: white;
            color: black;
        }
        .btn {
            padding: 8px 12px;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }
        .edit {
            background: blue;
        }
        .delete {
            background: red;
        }
        .edit:hover {
            background: darkblue;
        }
        .delete:hover {
            background: darkred;
        }
        .add {
            background: green;
            padding: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .add:hover {
            background: darkgreen;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Bikes</h2>
        <a href="add_bike.php" class="btn add">Add New Bike</a>
        <table>
            <tr>
                <th>Bike ID</th>
                <th>Model</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Rent per Hour</th>
                <th>Availability</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['bike_id'] ?></td>
                    <td><?= $row['model'] ?></td>
                    <td><?= $row['brand'] ?></td>
                    <td><?= $row['type'] ?></td>
                    <td>₹<?= $row['rent_per_hour'] ?></td>
                    <td><?= $row['availability'] ? "Available" : "Not Available" ?></td>
                    <td>
                        <a href="edit_bike.php?bike_id=<?= $row['bike_id'] ?>" class="btn edit">Edit</a>
                        <a href="delete_bike.php?bike_id=<?= $row['bike_id'] ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

<?php closeConnection($conn); ?>
