<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url('https://images.unsplash.com/photo-1585640120755-9f0239a16f4a?w=1200&auto=format&fit=cover&q=80') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            width: 100%;
        }

        .welcome {
            font-size: 50px;
            font-weight: bold;
        }

        .logout-btn {
            background: linear-gradient(45deg, #333, #000);
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 5px 10px rgba(255, 255, 255, 0.2);
            text-align: center;
            text-decoration: none;
            color: white;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: linear-gradient(45deg, #555, #222);
            transform: scale(1.05);
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }

        .btn {
            background: linear-gradient(45deg, #222, #000);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 15px;
            box-shadow: 0 5px 10px rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }

        .btn:hover {
            background: linear-gradient(45deg, #444, #111);
            transform: scale(1.05);
        }

    </style>
</head>
<body>

    <div class="header">
        <h1 class="welcome">Welcome,  <?php echo $_SESSION['customer_name']; ?> !</h1>
        <a href="customer_logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
        <a href="available_bikes.php" class="btn">View Available Bikes</a>
        <a href="my_rentals.php" class="btn">My Rentals</a>
        <a href="update_profile.php" class="btn">Update Password</a>
    </div>

</body>
</html>
