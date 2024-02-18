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
$jomPickID = ($matricNumber != null) ? $matricNumber : generateMatriculationNumber(); // Use matricNumber if provided, else generate a new one

// Check if the email address already exists
$emailCheckSql = "SELECT emailAddress FROM user WHERE emailAddress = '" . $emailAddress . "'";
$emailCheckResult = mysqli_query($db, $emailCheckSql);
$emailCount = mysqli_num_rows($emailCheckResult);

if ($emailCount == 1) {
    echo json_encode("ErrorEmail");
} else {
    // Assign role_id based on userType
    switch ($userType) {
        case 'student':
            $role_id = 6; // Set the role_id to 3 for student
            break;
        case 'staff':
            $role_id = 4; // Set the role_id to 2 for staff
            break;
        case 'public':
            $role_id = 7; // Set the role_id to 4 for public
            if ($matricNumber == null) {
                $matricNumber = generateMatriculationNumber(); // Generate matriculation number for public users
            }
            break;
        default:
            $role_id = 6; // Default to student if userType is not recognized
    }

    $usernameCheckSql = "SELECT username FROM user WHERE username = '" . $username . "'";
    $usernameCheckResult = mysqli_query($db, $usernameCheckSql);
    $usernameCount = mysqli_num_rows($usernameCheckResult);

    if ($usernameCount == 1) {
        echo json_encode("ErrorUsername");
    } else {
        // Check if the phone number already exists
        $phoneNumberCheckSql = "SELECT phoneNumber FROM user WHERE phoneNumber = '" . $phoneNumber . "'";
        $phoneNumberCheckResult = mysqli_query($db, $phoneNumberCheckSql);
        $phoneNumberCount = mysqli_num_rows($phoneNumberCheckResult);

        if ($phoneNumberCount > 0) {
            echo json_encode("ErrorPhoneNumber");
        } else {
            // Check if the JomPick_ID already exists
            $jomPickIDCheckSql = "SELECT JomPick_ID FROM user WHERE JomPick_ID = '" . $jomPickID . "'";
            $jomPickIDCheckResult = mysqli_query($db, $jomPickIDCheckSql);
            $jomPickIDCount = mysqli_num_rows($jomPickIDCheckResult);

            if ($jomPickIDCount > 0) {
                echo json_encode("ErrorJomPickID");
            } else {
                $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                $insert = "INSERT INTO user(username, password, fullName, emailAddress, icNumber, phoneNumber, role_id, JomPick_ID, securityQuestion1, securityQuestion2) 
                           VALUES ('" . $username . "','" . $hashPassword . "', '" . $fullName . "', '" . $emailAddress . "', '" . $icNumber . "', '" . $phoneNumber . "', '" . $role_id . "', '" . $jomPickID . "', '" . $securityQuestion1 . "', '" . $securityQuestion2 . "')";
                $query = mysqli_query($db, $insert);

                if ($query) {
                    echo json_encode("Success");
                }
            }
        }
    }
}

function generateMatriculationNumber() {
    // Generate a random 9-digit number
    $randomDigits = strval(100000000 + rand(0, 900000000));

    // Combine the prefix 'P' with the random digits
    return 'P' . $randomDigits;
}
?>
