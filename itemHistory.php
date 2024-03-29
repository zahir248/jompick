<?php

// Connect to your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jompick";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user ID from the request
//$userID = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$JomPick_ID = isset($_GET['JomPick_ID']) ? $_GET['JomPick_ID'] : null;

if ($JomPick_ID !== null) {
    // Fetch user data based on the user ID
    $sql = "SELECT item_management.item_id, item.name, item_management.registerDate, item_management.confirmation_id,
    item_management.JomPick_ID, item_management.dueDate_id,  due_date.dueDate,
    item.trackingNumber, item_type.name AS itemType, item.image, confirmation.imageProof, confirmation.pickUpDate,
    confirmation.confirmationStatus_id, confirmation_status.status, 
    confirmation.pickUpLocation_id, pickup_location.address, pickup_location.name AS pickUpName
    FROM item_management
    INNER JOIN user ON item_management.JomPick_ID = user.JomPick_ID
    INNER JOIN item ON item_management.item_id = item.item_id
    INNER JOIN item_type ON item.itemType_id = item_type.itemType_id
    INNER JOIN due_date ON item_management.dueDate_id = due_date.dueDate_id
    INNER JOIN confirmation ON item_management.confirmation_id = confirmation.confirmation_id
    INNER JOIN confirmation_status ON confirmation.confirmationStatus_id = confirmation_status.confirmationStatus_id
    INNER JOIN pickup_location ON confirmation.pickUpLocation_id = pickup_location.pickUpLocation_id 
    WHERE item_management.JomPick_ID = $JomPick_ID
    AND confirmation_status.status IN ('Picked', 'Disposed')";

    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Convert the result set to an associative array
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $row['image'] = base64_encode($row['image']);
                $row['imageProof'] = base64_encode($row['imageProof']);
                $rows[] = $row;
            }

            // Return the data as JSON
            echo json_encode($rows);
        } else {
            echo "0 results";
        }

        // Close the result set
        $result->close();
    } else {
        // Handle query execution error
        die("Error executing the query: " . $conn->error);
    }
} else {
    echo "Missing 'user_id' parameter";
}

// Close the database connection
$conn->close();
?>
