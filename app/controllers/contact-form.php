<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/User.php";
$user = new User();

if(!isset($_SESSION['user_id'])){
    redirect("/public/index.php?page=login","error","You must be logged in first");
}
$user_id = $_SESSION['user_id'];

// Validate required fields
if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['message'])) {
         redirectBack( "error", "All fields are required.");
}

// Sanitize inputs
$name    = trim($_POST['username']);
$email   = trim($_POST['email']);
$message = trim($_POST['message']);
if (strlen($name) < 3 || strlen($name) > 50) {
             redirectBack( "error", "Name must be between 3 and 50 characters.");
}

if (strlen($message) < 10) {
             redirectBack( "error", "Message must be at least 10 characters long.");
}

if (strlen($message) > 2000) {
             redirectBack( "error", "Message is too long. Limit is 2000 characters.");
}
if(strlen($email) > 50){
             redirectBack( "error", "email is too long");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             redirectBack( "error", "Invalid email address.");

}


$result = $user->addContactMessage($user_id, $name, $email, $message);

if ($result) { 
             redirectBack( "success", "message sent ! we will contact you soon");

} else {
             redirectBack( "error", "Internal server error");
}

?>