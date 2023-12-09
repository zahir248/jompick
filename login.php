<?php
$db = mysqli_connect('localhost', 'root', '', 'jompick');
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "SELECT * FROM user WHERE username = '" . $username . "' AND password = '" . $password . "'";
$result = mysqli_query($db, $sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    // Retrieve the user information from the query result
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];
    $icNumber = $row['icNumber']; // Assuming icNumber is a column in the 'user' table
    $fullName = $row['fullName']; // Assuming fullName is a column in the 'user' table
    $phoneNumber = $row['phoneNumber']; // Assuming phoneNumber is a column in the 'user' table
    $emailAddress = $row['emailAddress']; // Assuming emailAddress is a column in the 'user' table

    // Assuming 'image' is the column in the 'user' table that stores the image as longblob
    $image = base64_encode($row['image']); // Encode the image data as base64

    // Create a JSON response array
    $response = array(
        "status" => "Success",
        "user_id" => $user_id,
        "icNumber" => $icNumber,
        "fullName" => $fullName,
        "phoneNumber" => $phoneNumber,
        "emailAddress" => $emailAddress,
        "image" => $image
    );

    // Encode the array as JSON and echo it
    echo json_encode($response);
} else {
    // Create an error response
    $response = array(
        "status" => "Error"
    );

    // Encode the array as JSON and echo it
    echo json_encode($response);
}
?>
