<?php

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

if($userID !== null){
    $sql = "SELECT item_management.user_id, item_management.confirmation_id, item_management.dueDateStatus_id, item_management.item_id, 
    confirmation.status,user.userName, confirmation.pickUpLocation_id, pickup_location.address, due_date_status.dueDate, item.name, item_management.registerDate 
    FROM item_management 
    INNER JOIN user ON item_management.user_id = user.user_id
    INNER JOIN item ON item_management.item_id = item.item_id 
    INNER JOIN confirmation ON item_management.confirmation_id = confirmation.confirmation_id 
    INNER JOIN pickup_location ON item_management.confirmation_id =confirmation.confirmation_id AND confirmation.pickUpLocation_id = pickup_location.pickUpLocation_id 
    INNER JOIN due_date_status ON item_management.dueDateStatus_id = due_date_status.dueDateStatus_id 
    WHERE item_management.user_id = 80";


 $result = $conn->query($sql);

 if ($result) {
     if ($result->num_rows > 0) {
         // Convert the result set to an associative array
         $rows = array();
         while ($row = $result->fetch_assoc()) {
             //$row['image'] = base64_encode($row['image']);
             unset($row['image']);
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
}

 $conn->close();

?>