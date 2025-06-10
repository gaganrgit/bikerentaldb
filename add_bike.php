<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $conn = openConnection();

    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $rent_per_hour = $_POST['rent_per_hour'];

    $query = "INSERT INTO Bikes (admin_id, model, brand, type, rent_per_hour, availability) 
              VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssd", $_SESSION['admin_id'], $model, $brand, $type, $rent_per_hour);
    
    if ($stmt->execute()) {
        header("Location: manage_bikes.php");
        exit();
    }

    closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Bike</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: black;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            background: white;
            color: black;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background: linear-gradient(45deg, #333, #000);
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(45deg, #555, #222);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Bike</h2>
        <form method="post">
            <label>Model:</label>
            <input type="text" name="model" required>

            <label>Brand:</label>
            <input type="text" name="brand" required>

            <label>Type:</label>
            <select name="type">
                <option value="Scooter">Scooter</option>
                <option value="Motorbike">Motorbike</option>
                <option value="Sports Bike">Sports Bike</option>
            </select>

            <label>Rent per Hour (₹):</label>
            <input type="number" step="0.01" name="rent_per_hour" required>

            <button type="submit">Add Bike</button>
        </form>
    </div>
</body>
</html>
