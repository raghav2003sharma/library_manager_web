<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/User.php";
$user = new User();
if(
    empty($_POST['username']) ||
    empty($_POST['email']) ||
    empty($_POST['password'])
){
        redirectBack( "error", "All fields are required.");

}
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$role = $_POST['role'] ?? 'user';
if (strlen($username) < 3) {
    redirectBack( "error", "Username must be at least 3 characters long.");

   
}

// Max length
if (strlen($username) > 30) {
         redirectBack( "error", "Username cannot exceed 30 characters.");
}
// Only letters 
if (!preg_match("/^[A-Za-z ]+$/", $username)) {
         redirectBack( "error", "Username can contain only letters.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         redirectBack( "error", "Invalid email format.");
}
if (strlen($email) > 50) {
         redirectBack( "error", "Email is too long.");
}
//  Check if email exists
$checkEmailResult = $user->getUserByEmail($email);

if ($checkEmailResult->num_rows > 0) {
     redirectBack( "error", "Email already exists.");

}
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$result = $user->addUser($username, $email, $hashedPassword, $role);
if($result){
         redirectBack( "success", "User added successfully.");
} else {
         redirectBack( "error", "Error adding user.");
}
?>