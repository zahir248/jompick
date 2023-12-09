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
$newPickupDate = $_POST['newPickupDate'];

// Check if the user has already extended the pick-up date
$checkQuery = "SELECT c.extendedPickup 
               FROM confirmation c
               JOIN item_management im ON c.confirmation_id = im.confirmation_id
               WHERE im.item_id = $itemId";

$result = mysqli_query($conn, $checkQuery);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    if ($row['extendedPickup'] == 1) {
        echo "You have already extended the pick-up date before.";
    } else {
        // Perform the update query
        $updateQuery = "UPDATE confirmation c
                        JOIN item_management im ON c.confirmation_id = im.confirmation_id
                        SET c.confirmationDate = '$newPickupDate', c.extendedPickup = 1
                        WHERE im.item_id = $itemId";

        if (mysqli_query($conn, $updateQuery)) {
            echo "Update successful";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
} else {
    echo "Error checking extended pick-up status: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
