<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = openConnection();

if (isset($_GET['bike_id'])) {
    $bike_id = $_GET['bike_id'];
    $query = "SELECT * FROM Bikes WHERE bike_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bike_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $rent_per_hour = $_POST['rent_per_hour'];
    $availability = $_POST['availability'];

    $query = "UPDATE Bikes SET model=?, brand=?, type=?, rent_per_hour=?, availability=? WHERE bike_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssdis", $model, $brand, $type, $rent_per_hour, $availability, $bike_id);

    if ($stmt->execute()) {
        header("Location: manage_bikes.php");
        exit();
    }
}

closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bike</title>
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
        <h2>Edit Bike</h2>
        <form method="post">
            <label>Model:</label>
            <input type="text" name="model" value="<?= htmlspecialchars($result['model']) ?>" required>

            <label>Brand:</label>
            <input type="text" name="brand" value="<?= htmlspecialchars($result['brand']) ?>" required>

            <label>Type:</label>
            <input type="text" name="type" value="<?= htmlspecialchars($result['type']) ?>" required>

            <label>Rent per Hour (₹):</label>
            <input type="number" step="0.01" name="rent_per_hour" value="<?= htmlspecialchars($result['rent_per_hour']) ?>" required>

            <label>Availability:</label>
            <select name="availability">
                <option value="1" <?= $result['availability'] ? "selected" : "" ?>>Available</option>
                <option value="0" <?= !$result['availability'] ? "selected" : "" ?>>Not Available</option>
            </select>

            <button type="submit">Update Bike</button>
        </form>
    </div>
</body>
</html>
