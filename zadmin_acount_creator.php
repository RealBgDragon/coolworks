<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create an account
function createAdminAccount($conn, $adminUsername, $adminEmail, $adminPassword)
{
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Hash the password
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

    // Bind the parameters
    $stmt->bind_param("sss", $adminUsername, $adminEmail, $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New admin account created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Example usage
$adminUsername = "Test";
$adminEmail = "test06200508@gmail.com";
$adminPassword = "123";
createAdminAccount($conn, $adminUsername, $adminEmail, $adminPassword);

// Close the connection
$conn->close();
