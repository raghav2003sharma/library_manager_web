<?php
session_start();
require_once "../../helpers/helpers.php";
require_once "../../models/User.php";
$user = new User();

if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['role'])){
          redirectBack( "error", "All fields are required.");

}
$user_id = $_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$role = trim($_POST['role']);
if (strlen($name) < 3) {
      redirectBack( "error", "Username must be at least 3 characters long.");

}

// Max length
if (strlen($name) > 30) {
    redirectBack( "error", "Username cannot exceed 30 characters.");

}
// Only letters 
if (!preg_match("/^[A-Za-z ]+$/", $name)) {
     redirectBack( "error", "Username can contain only letters.");

}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          redirectBack( "error", "Invalid email format.");

}
if (strlen($email) > 50) {
          redirectBack( "error", "Email is too long.");

}
// CHECK IF EMAIL ALREADY EXISTS (BUT NOT FOR CURRENT USER)
$checkResult = $user->getOtherUsersByEmail($email, $user_id);

if ($checkResult->num_rows > 0) {
         redirectBack( "error", "This email is already used by another account.");
}
$result = $user->updateUser($name, $email, $role, $user_id);
if($result){
         redirectBack( "success", "User updated successfully.");
} else {
         redirectBack( "error", "Error updating user.");
}


?>