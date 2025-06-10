<?php
session_start();
include 'db_connection.php';
$conn = openConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $query = "SELECT * FROM customer WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['customer_id'] = $row['customer_id'];
                $_SESSION['customer_name'] = $row['name'];  

                echo "<script>alert('Login Successful!'); window.location.href='dashboard.php';</script>";
                exit();
            } else {
                echo "<script>alert('Invalid Password! Please try again.');</script>";
            }
        } else {
            echo "<script>alert('User not found! Please sign up.');</script>";
        }
    }

    if (isset($_POST['signup'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
        $phone = trim($_POST['phone']);

        $checkQuery = "SELECT * FROM customer WHERE email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $insertQuery = "INSERT INTO customer (name, email, password, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $name, $email, $password, $phone);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! Please login.'); window.location.href='customer_login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Registration failed! Try again.');</script>";
            }
        } else {
            echo "<script>alert('Email already exists! Please login.');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login / Signup</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1636647393235-a06b0f515a3a?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTUxfHxzcG9ydHMlMjBiaWtlfGVufDB8fDB8fHww') no-repeat center center/cover;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: white;
        }
        .container {
            background: transparent; /* Removed the black box */
            padding: 20px;
            text-align: center;
            width: 350px;
        }
        h2 {
            background: rgba(0, 0, 0, 0.6);
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8);
            color: black;
        }
        .btn {
            background: linear-gradient(45deg, #000, #333);
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: 0.3s;
            border-radius: 5px;
        }
        .btn:hover {
            background: linear-gradient(45deg, #444, #000);
        }
        .toggle {
            margin-top: 15px;
            cursor: pointer;
            color: #ffcc00;
        }
    </style>
</head>
<body>
    <div class="container" id="loginContainer">
        <h2 id="formTitle">Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
        <p class="toggle" onclick="toggleForm()">Don't have an account? Sign Up</p>
    </div>

    <div class="container" id="signupContainer" style="display: none;">
        <h2>Sign Up</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <button type="submit" name="signup" class="btn">Sign Up</button>
        </form>
        <p class="toggle" onclick="toggleForm()">Already have an account? Login</p>
    </div>

    <script>
        function toggleForm() {
            document.getElementById("loginContainer").style.display = 
                document.getElementById("loginContainer").style.display === "none" ? "block" : "none";
            document.getElementById("signupContainer").style.display = 
                document.getElementById("signupContainer").style.display === "none" ? "block" : "none";
        }
    </script>
</body>
</html>
