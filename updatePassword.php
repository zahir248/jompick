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
$userId = $_POST['user_id'];
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];

// Check if the submitted old password matches the existing hashed password
$sqlCheck = "SELECT * FROM user WHERE user_id = $userId";
$resultCheck = $conn->query($sqlCheck);

$response = array(); // Create an associative array for the response

if ($resultCheck->num_rows > 0) {
    $row = $resultCheck->fetch_assoc();
    $hashedPassword = $row['password'];

    // Verify the old password
    if (password_verify($oldPassword, $hashedPassword)) {
        // Old password matches, hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $sqlUpdate = "UPDATE user SET password = '$hashedNewPassword' WHERE user_id = $userId";

        if ($conn->query($sqlUpdate) === TRUE) {
            $response['success'] = true;
            $response['message'] = "Password updated successfully";
        } else {
            $response['success'] = false;
            $response['message'] = "Error updating password: " . $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Old password is incorrect";
    }
} else {
    $response['success'] = false;
    $response['message'] = "User not found";
}

// Close the database connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
