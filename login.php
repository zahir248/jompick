<?php

include 'db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username = '" . $username . "'";
$result = mysqli_query($db, $sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    // Retrieve the user information from the query result
    $row = mysqli_fetch_assoc($result);

    // Get the hashed password from the database
    $storedHashedPassword = $row['password'];

    // Verify the entered password against the stored hash
    if (password_verify($password, $storedHashedPassword)) {
        // Password is correct

        // Fetch other user information as needed
        $user_id = $row['user_id'];
        $icNumber = $row['icNumber'];
        $fullName = $row['fullName'];
        $phoneNumber = $row['phoneNumber'];
        $emailAddress = $row['emailAddress'];
        $JomPick_ID = $row['JomPick_ID'];
        $image = base64_encode($row['image']);
        $role_id = $row['role_id'];

        // Fetch the role information from the 'role' table based on the 'role_id'
        $role_sql = "SELECT * FROM role WHERE role_id = '" . $role_id . "'";
        $role_result = mysqli_query($db, $role_sql);
        $role_row = mysqli_fetch_assoc($role_result);
        $rolename = $role_row['rolename'];

        // Create a JSON response array
        $response = array(
            "status" => "Success",
            "user_id" => $user_id,
            "icNumber" => $icNumber,
            "fullName" => $fullName,
            "phoneNumber" => $phoneNumber,
            "emailAddress" => $emailAddress,
            "JomPick_ID" => $JomPick_ID,
            "image" => $image,
            "rolename" => $rolename
        );

        // Encode the array as JSON and echo it
        echo json_encode($response);
    } else {
        // Password is incorrect
        $response = array(
            "status" => "Error"
        );
        echo json_encode($response);
    }
} else {
    // No user found with the given username
    $response = array(
        "status" => "Error"
    );
    echo json_encode($response);
}
?>

