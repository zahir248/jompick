<?php
$db = mysqli_connect('localhost', 'root', '', 'jompick');
if (!$db) {
    echo "Database connection failed";
}

$username = $_POST['username'];
$password = $_POST['password'];
$fullName = $_POST['fullName'];
$emailAddress = $_POST['emailAddress'];
$icNumber = $_POST['icNumber'];
$phoneNumber = $_POST['phoneNumber'];
$userType = $_POST['userType'];
$matricNumber = isset($_POST['matricNumber']) ? $_POST['matricNumber'] : null;
$securityQuestion1 = $_POST['securityQuestion1'];
$securityQuestion2 = $_POST['securityQuestion2'];

// Assign role_id based on userType
switch ($userType) {
    case 'student':
        $role_id = 3; // Set the role_id to 3 for student
        break;
    case 'staff':
        $role_id = 2; // Set the role_id to 2 for staff
        break;
    case 'public':
        $role_id = 4; // Set the role_id to 4 for public
        break;
    default:
        $role_id = 3; // Default to student if userType is not recognized
}

$sql = "SELECT username FROM user WHERE username = '" . $username . "'";
$result = mysqli_query($db, $sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    echo json_encode("Error");
} else {
    $insert = "INSERT INTO user(username, password, fullName, emailAddress, icNumber, phoneNumber, role_id, matricNumber, securityQuestion1, securityQuestion2) 
               VALUES ('" . $username . "','" . $password . "', '" . $fullName . "', '" . $emailAddress . "', '" . $icNumber . "', '" . $phoneNumber . "', '" . $role_id . "', '" . $matricNumber . "', '" . $securityQuestion1 . "', '" . $securityQuestion2 . "')";
    $query = mysqli_query($db, $insert);

    if ($query) {
        echo json_encode("Success");
    }
}
?>
