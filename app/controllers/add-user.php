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
if (strlen($username) < 3) {
    $_SESSION['error'] = "Username must be at least 3 characters long.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}

// Max length
if (strlen($username) > 30) {
    $_SESSION['error'] = "Username cannot exceed 30 characters.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
// Only letters (and optional spaces)
if (!preg_match("/^[A-Za-z ]+$/", $username)) {
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
//  Check if email exists
$checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$checkEmailResult = $checkEmail->get_result();

if ($checkEmailResult->num_rows > 0) {
    $_SESSION['error'] = "Email already exists.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
if($stmt->execute()){
    $_SESSION['success'] = "User added successfully.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
} else {
    $_SESSION['error'] = "Error adding user.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
?>