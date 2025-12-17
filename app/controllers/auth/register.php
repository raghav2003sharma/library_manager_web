<?php
session_start();
require_once "../../helpers/helpers.php";
require_once "../../models/User.php";
$user = new User();
if (
    empty($_POST['username']) ||
    empty($_POST['email']) ||
    empty($_POST['password']) ||
    empty($_POST['confirm_password'])
) {
    redirectBack( "error", "All fields are required.");

}
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

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
if (strlen($password) < 6) {
         redirectBack( "error", "Password must be at least 6 characters.");
}

if ($password !== $confirmPassword) {
             redirectBack( "error", "Password and cconfirm password do not match.");

}

$exists = $user->getUserByEmail($email);
 if($exists->num_rows > 0){
        redirectBack( "error", "Email already exists.");

 }

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


$user_id = $user->register($username,$email,$hashedPassword);
if ($user_id) {
     $_SESSION['user_id'] = $user_id;
        $_SESSION['name'] = $username;
        $_SESSION['role'] = "user";
        $_SESSION['email'] = $email;
           header("Location: /user-home");

} else {
     redirectBack("error","error in registering user.");
}

?>