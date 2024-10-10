<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost"; // Change this if your database is hosted elsewhere
$dbname = 'form_process'; // Make sure this matches your database name
$username = 'root'; // Change this if your database has a different username
$password = ''; // Change to your actual database password

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data and validate
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Check if any field is empty
    if (empty($name) || empty($email) || empty($message)) {
        echo "error: All fields are required!";
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "error: Invalid email format!";
        } else {
            // Prepare an SQL statement
            $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
            if ($stmt === false) {
                echo "error: Failed to prepare the statement!";
            } else {
                // Bind parameters
                $stmt->bind_param("sss", $name, $email, $message); // "sss" means three string parameters

                // Execute the statement
                if ($stmt->execute()) {
                    echo "success"; // Return success response
                } else {
                    echo "error: Failed to execute the statement!";
                }

                // Close the statement
                $stmt->close();
            }
        }
    }

    // Close the connection
    $conn->close();
} else {
    echo "error: Invalid request method!";
}
?>
