<?php
session_start();
require_once "../../config/db.php";
require_once "../models/User.php";
$user = new User();

if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['role'])){
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
$user_id = $_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$role = trim($_POST['role']);
if (strlen($name) < 3) {
    $_SESSION['error'] = "Username must be at least 3 characters long.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}

// Max length
if (strlen($name) > 30) {
    $_SESSION['error'] = "Username cannot exceed 30 characters.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
// Only letters (and optional spaces)
if (!preg_match("/^[A-Za-z ]+$/", $name)) {
    $_SESSION['error'] = "Username can contain only letters.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
if (strlen($email) > 50) {
    $_SESSION['error'] = "Email is too long.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
// CHECK IF EMAIL ALREADY EXISTS (BUT NOT FOR CURRENT USER)
$check = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check->bind_param("si", $email, $user_id);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    $_SESSION['error'] = "This email is already used by another account.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
// $sql = "UPDATE users SET name = ?,email=?,role=? WHERE user_id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("sssi", $name, $email, $role, $user_id);
$result = $user->updateUser($name, $email, $role, $user_id);
if($result){
    $_SESSION['success'] = "User updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update user.";
}
header("Location: /public/index.php?page=admin-home&main-page=manage-users");


?>