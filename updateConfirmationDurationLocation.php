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

// Get the POST data from the Flutter app
$itemId = $_POST['itemId'];
$pickUpDuration = $_POST['pickUpDuration'];
$currentLocation = $_POST['currentLocation'];
$pickupType = $_POST['pickupType']; // Add this line to get pickupType

// Perform the update query
$updateQuery = "UPDATE confirmation 
                SET pickUpDuration = '$pickUpDuration', 
                    currentLocation = '$currentLocation',
                    pickupType = '$pickupType'
                WHERE confirmation_id = (
                    SELECT im.confirmation_id
                    FROM item_management im
                    WHERE im.item_id = $itemId
                )";

if (mysqli_query($conn, $updateQuery)) {
    echo "Update successful";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
