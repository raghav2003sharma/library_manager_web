<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/User.php";
$user = new User();
if (!isset($_SESSION['user_id'])) {
       redirect("/public/index.php?page=login","error","You must be logged in first");

}

$user_id = $_SESSION['user_id'];
$name  = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

// BASIC VALIDATION
if ($name === '' || $email === '') {
     redirectBack( "error", "Name and Email are required.");
}

// EMAIL FORMAT VALIDATION
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectBack( "error", "Invalid email format.");

}
// CHECK IF EMAIL ALREADY EXISTS (BUT NOT FOR CURRENT USER)
$checkResult = $user->getOtherUsersByEmail($email, $user_id);
if ($checkResult->num_rows > 0) {
     redirectBack( "error", "This email is already used by another account.");

}


$update = $user->updateUser($name, $email, $user_id);
if ($update) {
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
        redirectBack( "success", "Profile updated successfully!");

} else {
    redirectBack( "error", "Failed to update profile");
}
?>
