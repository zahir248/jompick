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
$newPickupDate = $_POST['newPickupDate'];

// Convert the string to a DateTime object
$dateTime = new DateTime($newPickupDate);

// Extract the date part only
$newPickupDate = $dateTime->format('Y-m-d');

// Perform the update query
$updateQuery = "UPDATE confirmation 
                SET pickUpDate = '$newPickupDate'
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