<?php
$servername = "localhost:8088"; // Remove port if XAMPP uses default (3306)
$port = 8088; // Keep the custom port if needed
$username = "root";  
$password = "";  
$database = "bikerentaldb";  

// Function to open database connection
function openConnection() {
    global $servername, $port, $username, $password, $database; // Include port
    $conn = new mysqli($servername, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to close database connection
function closeConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}
?>
