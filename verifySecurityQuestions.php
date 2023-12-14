<?php
$db = mysqli_connect('localhost', 'root', '', 'jompick');
if (!$db) {
    echo "Database connection failed";
}

$answer1 = $_POST['answer1'];
$answer2 = $_POST['answer2'];

// Assuming your database table structure has columns 'securityQuestion1' and 'securityQuestion2'
$sql = "SELECT username FROM user WHERE securityQuestion1 = '$answer1' AND securityQuestion2 = '$answer2'";
$result = mysqli_query($db, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        // Answers are correct and linked to a user
        $response = [
            'verified' => true,
            'username' => $row['username']
        ];
        echo json_encode($response);
    } else {
        // Answers are incorrect or not linked to a user
        $response = ['verified' => false];
        echo json_encode($response);
    }
} else {
    // Error in executing the query
    $response = ['error' => 'Query execution failed'];
    echo json_encode($response);
}
?>
