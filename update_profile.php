<?php
session_start();
include "db_connection.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$conn = openConnection(); // Open database connection

$customer_id = $_SESSION['customer_id'];
$message = "";

// Fetch user data
$query = "SELECT name, email, phone FROM customer WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch stored password
    $query = "SELECT password FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    // Verify current password
    if (password_verify($current_password, $db_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_query = "UPDATE customer SET password = ? WHERE customer_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $hashed_password, $customer_id);
            if ($stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $message = "Error updating password.";
            }
            $stmt->close();
        } else {
            $message = "New passwords do not match.";
        }
    } else {
        $message = "Current password is incorrect.";
    }
}

closeConnection($conn); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1642025967715-0410af8d7077?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fHBhc3N3b3JkfGVufDB8fDB8fHww') no-repeat center center/cover;
            color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 50px;
            width: 40%;
            padding: 20px;
        }
        h2 {
            font-size: 40px;
            font-weight: bold;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            background: black;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: 2px solid white;
            transition: 0.3s ease;
        }
        button:hover {
            background: white;
            color: black;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            background: red;
            padding: 10px 20px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }
        .logout-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <a href="customer_login.php" class="logout-btn">Logout</a>
    <div class="container">
        <h2>Update Password</h2>
        <p><?php echo $message; ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>

        <h3>Change Password</h3>
        <form method="POST">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>