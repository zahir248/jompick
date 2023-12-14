<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jompick";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Receive updated password data from the request
$username = $_POST['username']; // Assuming you are using the username for authentication
$newPassword = $_POST['newPassword'];

// Check if the submitted username and email match an existing user
$sqlCheck = "SELECT * FROM user WHERE username = '$username';";
$resultCheck = $conn->query($sqlCheck);

$response = array(); // Create an associative array for the response

if ($resultCheck->num_rows > 0) {
    // User found, update the user's password in the database
    $sqlUpdate = "UPDATE user SET password = '$newPassword' WHERE username = '$username';";

    if ($conn->query($sqlUpdate) === TRUE) {
        $response['success'] = true;
        $response['message'] = "Password updated successfully";
    } else {
        $response['success'] = false;
        $response['message'] = "Error updating password: " . $conn->error;
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid username or email";
}

// Close the database connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
