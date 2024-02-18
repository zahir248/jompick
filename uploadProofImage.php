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

// Receive confirmation ID and image data from the request
$confirmationId = $_POST['confirmationId'];

// Check if an image is sent
if(isset($_FILES['imageProof'])) {
    $imageProof = file_get_contents($_FILES['imageProof']['tmp_name']); // Read the image content as binary data

    // Insert the image into the confirmation table in the database
    $sql = "UPDATE confirmation SET imageProof = ? WHERE confirmation_id = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("si", $imageProof, $confirmationId);

    // Execute the statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Image uploaded successfully";
    } else {
        $response['success'] = false;
        $response['message'] = "Error uploading image: " . $stmt->error;
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