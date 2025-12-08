<?php
session_start();
require_once "../../config/db.php";

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
$check = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check->bind_param("si", $email, $user_id);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    $_SESSION['error'] = "This email is already used by another account.";
    header("Location: /public/index.php?page=user-home");
    exit;
}

$update = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
$update->bind_param("ssi", $name, $email, $user_id);

if ($update->execute()) {
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    $_SESSION['success'] = "Profile updated successfully!";
} else {
    $_SESSION['error'] = "Failed to update profile.";
}

header("Location: /public/index.php?page=user-home");
exit;

?>
