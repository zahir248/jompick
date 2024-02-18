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


$userID = isset($_GET['user_id']) ? $_GET['user_id'] : null;


if($userID !== null) {
    // Fetch overdue item
    // $sql = "SELECT item_management.user_id, user.userName, user.image
    // FROM item_management
    // INNER JOIN user ON item_management.user_id = user.user_id
    // WHERE item_management.user_id = $userID";

    $sql = "SELECT user.user_id, user.userName, user.image, user.JomPick_ID
    FROM user
    WHERE user.user_id = $userID";

    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Convert the result set to an associative array
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $row['image'] = base64_encode($row['image']);
                //unset($row['image']);
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