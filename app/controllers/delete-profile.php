<?php
session_start();
require_once "../models/User.php";
$user = new User();

// Must be logged in
if (!isset($_SESSION['user_id'])) {
     header("Location: /public/index.php?page=login");
    exit;
}

$user_id = $_SESSION['user_id'];
$isDeleted = $user->deleteUserCompletely($user_id);

if ($isDeleted) {
session_unset();
session_destroy();
$_SESSION['success'] ="user deleted successfully";
header("Location: /public/index.php?page=user-home");
exit;
} else {
    $_SESSION['error'] = "Failed to delete the user";
header("Location: /public/index.php?page=user-home");
exit;
}
