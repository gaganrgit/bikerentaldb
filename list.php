<?php
include "db_connection.php"; // Include database connection

$conn = openConnection(); // Open connection

$query = "SELECT bike_id, brand, type, model FROM bikes"; 
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike List</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1640971804623-0232a055fc57?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDMxfHx8ZW58MHx8fHx8') no-repeat center center/cover;
            color: white;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid white;
            text-align: center;
        }
        th {
            background: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Available Bikes</h2>
        <table>
            <tr>
                <th>Bike ID</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Model</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['bike_id']}</td>
                            <td>{$row['brand']}</td>
                            <td>{$row['type']}</td>
                            <td>{$row['model']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No bikes available</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php closeConnection($conn); // Close connection ?>
