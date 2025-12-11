<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/User.php";
$user = new User();
if (
    empty($_POST['cur-pass']) ||
    empty($_POST['new-pass']) ||
    empty($_POST['conf-pass'])
) {
    redirectBack( "error", "All fields are required.");

}
$user_id = $_SESSION['user_id'];
$currentPassword = $_POST['cur-pass'];
$newPassword = $_POST['new-pass'];
$confirmPassword = $_POST['conf-pass'];
if (strlen($newPassword) < 6) {
        redirectBack( "error", "New password must be at least 6 characters.");
}
if ($newPassword !== $confirmPassword) {
        redirectBack( "error", "New password and confirm password do not match.");

}
$result = $user->verifyCurrentPassword($user_id);

if ($result->num_rows === 0) {
        redirectBack( "error", "User not found.");

}
$userPassword = $result->fetch_assoc()['password'];
if (!password_verify($currentPassword, $userPassword)) {
        redirectBack( "error", "Current password is incorrect.");
}
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$isUpdated = $user->updatePassword( $hashedPassword, $user_id);
if ($isUpdated) {
        redirectBack( "success", "Password changed successfully.");
} else {
        redirectBack( "error", "Failed to change password. Please try again.");

   
}
?>