<?php
session_start();
require_once "../models/User.php";
$user = new User();

if(!isset($_SESSION['user_id'])){
        $_SESSION['error'] = "You must be logged in first";
 header("Location: /public/index.php?page=login");
     exit;
}
$user_id = $_SESSION['user_id'];

// Validate required fields
if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['message'])) {
        $_SESSION['error'] = "All fields are required";
     header("Location: /public/index.php?page=contact");
    exit;
}

// Sanitize inputs
$name    = trim($_POST['username']);
$email   = trim($_POST['email']);
$message = trim($_POST['message']);
if (strlen($name) < 3 || strlen($name) > 50) {
    $_SESSION['error'] = "Name must be between 3 and 50 characters.";
    header("Location: /public/index.php?page=contact");
    exit;
}

if (strlen($message) < 10) {
    $_SESSION['error'] = "Message must be at least 10 characters long.";
    header("Location: /public/index.php?page=contact");
    exit;
}

if (strlen($message) > 2000) {
    $_SESSION['error'] = "Message is too long. Limit is 2000 characters.";
    header("Location: /public/index.php?page=contact");
    exit;
}
if(strlen($email) > 50){
    $_SESSION['error'] = "email is too long";
    header("Location: /public/index.php?page=contact");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email address.";
    header("Location: /public/index.php?page=contact");
    exit;
}


$result = $user->addContactMessage($user_id, $name, $email, $message);

if ($result) { 
      $_SESSION['success'] = "message sent ! we will contact you soon";
     header("Location: /public/index.php?page=contact");
} else {
  $_SESSION['error'] = "Internal server error";
     header("Location: /public/index.php?page=contact");}
exit;

?>