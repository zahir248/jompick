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
$userID = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($userID !== null) {
    // Fetch user data based on the user ID
    $sql = "SELECT item_management.item_id, item.name, item.location, item_management.registerDate, 
                    item.trackingNumber, item_type.name AS itemType, item.image, confirmation.status, 
                    confirmation.confirmationDate, payment_status.status AS penaltyStatus, confirmation.imageProof
            FROM item_management
            INNER JOIN item ON item_management.item_id = item.item_id
            INNER JOIN item_type ON item.itemType_id = item_type.itemType_id
            INNER JOIN confirmation ON item_management.confirmation_id = confirmation.confirmation_id
            INNER JOIN due_date_status ON item_management.dueDateStatus_id = due_date_status.dueDateStatus_id
            INNER JOIN payment_status ON due_date_status.paymentStatus_id = payment_status.paymentStatus_id
            WHERE item_management.user_id = $userID 
                AND payment_status.status IN ('Paid', 'Unpaid')";

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
