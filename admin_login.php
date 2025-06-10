<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openConnection();
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if admin exists
    $query = "SELECT * FROM Admin WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {  // Direct string comparison (No Hashing)
            $_SESSION['admin_id'] = $row['admin_id'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('Admin not found!');</script>";
    }

    closeConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('https://images.unsplash.com/photo-1706624245895-be25324c6d71?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDc2fHx8ZW58MHx8fHx8') no-repeat center center/cover;
            text-align: center;
            margin: 0;
        }
        h2 {
            color: white;
            font-size: 28px;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 350px;
            padding: 20px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }
        .btn {
            background: linear-gradient(45deg,rgb(9, 15, 21),rgb(18, 22, 26));
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s ease;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }
        .btn:hover {
            background: linear-gradient(45deg,rgb(70, 74, 78),rgb(99, 107, 114));
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div>
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Admin Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
