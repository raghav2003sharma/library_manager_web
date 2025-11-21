<?php
session_start();
require_once "../../config/db.php";
if(
    empty($_POST['username']) ||
    empty($_POST['email']) ||
    empty($_POST['password'])
){
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$role = $_POST['role'] ?? 'user';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
if($stmt->execute()){
    $_SESSION['success'] = "User added successfully.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
} else {
    $_SESSION['error'] = "Error adding user. Email may already exist.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
?>