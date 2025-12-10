<?php
session_start();
require_once "../models/User.php";
$user = new User();
if (!isset($_SESSION['user_id'])) {
    header("Location: /public/index.php?page=login");
    exit;
}

$user_id = $_SESSION['user_id'];
$name  = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

// BASIC VALIDATION
if ($name === '' || $email === '') {
    $_SESSION['error'] = "Name and Email are required.";
    header("Location: /public/index.php?page=user-home");
    exit;
}

// EMAIL FORMAT VALIDATION
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: /public/index.php?page=user-home");
    exit;
}
// CHECK IF EMAIL ALREADY EXISTS (BUT NOT FOR CURRENT USER)
$checkResult = $user->getOtherUsersByEmail($email, $user_id);
if ($checkResult->num_rows > 0) {
    $_SESSION['error'] = "This email is already used by another account.";
    header("Location: /public/index.php?page=user-home");
    exit;
}


$update = $user->updateUser($name, $email,"user", $user_id);
if ($update) {
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    $_SESSION['success'] = "Profile updated successfully!";
} else {
    $_SESSION['error'] = "Failed to update profile.";
}

header("Location: /public/index.php?page=user-home");
exit;

?>
