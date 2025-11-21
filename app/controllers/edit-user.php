<?php
session_start();
require_once "../../config/db.php";

if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['role'])){
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
$user_id = $_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$role = trim($_POST['role']);

$sql = "UPDATE users SET name = ?,email=?,role=? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $name, $email, $role, $user_id);
if($stmt->execute()){
    $_SESSION['success'] = "User updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update user.";
}
header("Location: /public/index.php?page=admin-home&main-page=manage-users");


?>