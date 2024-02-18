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

// Receive updated profile data from the request
$userId = $_POST['user_id'];
$fullName = $_POST['fullName'];
$phoneNumber = $_POST['phoneNumber'];
$icNumber = $_POST['icNumber'];
$emailAddress = $_POST['emailAddress'];

// Check if an image is sent    
if(isset($_FILES['profile_image'])) {
    $image = file_get_contents($_FILES['profile_image']['tmp_name']); // Read the image content as binary data

    // Update the user's profile in the database
    $sql = "UPDATE user SET fullName=?, phoneNumber=?, icNumber=?, emailAddress=?, image=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssssi", $fullName, $phoneNumber, $icNumber, $emailAddress, $image, $userId);

    // Execute the statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Profile updated successfully";
        $response['fullName'] = $fullName;
        $response['phoneNumber'] = $phoneNumber;
        $response['icNumber'] = $icNumber;
        $response['emailAddress'] = $emailAddress;
    } else {
        $response['success'] = false;
        $response['message'] = "Error updating profile: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // No image sent
    $response['success'] = false;
    $response['message'] = "Image not provided.";
}

// Close the database connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

?>