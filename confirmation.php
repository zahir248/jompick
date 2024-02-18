<?php

// Connect to MySql Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jompick";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$status = $_POST['status'];
$pickUpDuration = $_POST['pickUpDuration'];
$currentLocation = $_POST['currentLocation'];
$pickUpLocation_id = $_POST['pickUpLocation_id'];

// Retrieve user id and item id from the request
$userID = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$itemID = isset($_GET['item_id']) ? $_GET['item_id'] : null;

if ($userID !== null && $itemID !== null) {
    // Update confirmation status
    $sql = "INSERT INTO confirmation (status, pickUpDuration, currentLocation, pickUpLocation_id) VALUES ('" . $status . "', '" . $pickUpDuration . "', '" . $currentLocation . "', '" . $pickUpLocation_id . "')";
    $query = $conn->query($sql);

    if ($query) {
        // Successfully inserted data
        $data = array('status' => 'success');
        echo json_encode($data);
    } else {
        // Failed to insert data
        $data = array('status' => 'error', 'message' => $conn->error);
        echo json_encode($data);
    }
} else {
    echo "Invalid user ID or Item ID";
}

$conn->close();

?>


<!-- $sql = "SELECT c.*, im.*
            FROM confirmation AS c
            INNER JOIN item_management AS im ON c.confirmation_id = im.confirmation_id
            WHERE im.user_id = $userID
            AND im.item_id = $itemID"; -->

<!-- UPDATE confirmation AS c
            INNER JOIN item_management AS im ON c.confirmation_id = im.confirmation_id
            SET c.status = 'picked'
            WHERE im.user_id = $userID
            AND im.item_id = $itemID -->


