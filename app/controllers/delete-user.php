<?php
session_start();
// require_once "../../config/db.php";
require_once "../models/User.php";
$user = new User();

if (empty($_POST['id'])) {
    $_SESSION['error'] = "User ID is required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
$user_id = $_POST['id'];
$isDeleted = $user->deleteUser($user_id);
if ($isDeleted) {
    $_SESSION['success'] = "User deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete user.";
}
header("Location: /public/index.php?page=admin-home&main-page=manage-users");

?>