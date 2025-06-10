<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1656420731047-3eb41c9d1dee?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDE4N3x8fGVufDB8fHx8fA%3D%3D') no-repeat center center/cover;
            color: white;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 5rem; /* Increased size */
            font-weight: bold;
        }
        .logout-btn {
            background: white;
            color: black;
            padding: 12px 24px;
            border: 2px solid white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            transition: 0.3s ease-in-out;
        }
        .logout-btn:hover {
            background: black;
            color: white;
            border: 2px solid white;
        }
        .dashboard-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .btn {
            background: white;
            color: black;
            padding: 14px;
            border: 2px solid white;
            cursor: pointer;
            font-size: 20px; /* Increased button text size */
            width: 260px;
            display: inline-block;
            margin: 15px;
            text-decoration: none;
            text-align: center;
            border-radius: 25px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }
        .btn:hover {
            background: black;
            color: white;
            border: 2px solid white;
            transform: scale(1.08);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Welcome, Admin</h2>
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="dashboard-container">
        <a href="manage_rentals.php" class="btn">Manage Rentals</a>
        <a href="manage_bikes.php" class="btn">Manage Bikes</a>
        <a href="manage_customers.php" class="btn">Manage Customers</a>
    </div>
</body>
</html>
