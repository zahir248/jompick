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

// Get the POST data from Flutter app
$itemId = $_POST['itemId'];
$status = $_POST['status'];

// Check if the user has already picked up the item
$checkQuery = "SELECT cs.status 
               FROM confirmation c
               JOIN item_management im ON c.confirmation_id = im.confirmation_id
               JOIN confirmation_status cs ON c.confirmationStatus_id = cs.confirmationStatus_id
               WHERE im.item_id = $itemId";

$result = mysqli_query($conn, $checkQuery);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    if ($row['status'] == 'Picked') {
        echo "You have already picked up your item";
    } elseif ($row['status'] == 'Pending' || 'Pick Now' ) {
        // Perform the update query
        $updateQuery = "UPDATE confirmation c
                        JOIN item_management im ON c.confirmation_id = im.confirmation_id
                        JOIN confirmation_status cs ON c.confirmationStatus_id = cs.confirmationStatus_id
                        SET c.confirmationStatus_id = '2', c.pickUpDate = NOW()
                        WHERE im.item_id = $itemId";

        if (mysqli_query($conn, $updateQuery)) {
            echo "Update successful";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid status in the database.";
    }
} else {
    echo "Error checking the item status: " . mysqli_error($conn);
}

mysqli_close($conn);
?>