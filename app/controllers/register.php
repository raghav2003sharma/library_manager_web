<?php
session_start();
require_once "../../config/db.php";
if (
    empty($_POST['username']) ||
    empty($_POST['email']) ||
    empty($_POST['password']) ||
    empty($_POST['confirm_password'])
) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=register");
    exit;
}
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

if (strlen($username) < 3) {
    $_SESSION['error'] = "Username must be at least 3 characters.";
    header("Location: /public/index.php?page=register");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format.";
    header("Location: /public/index.php?page=register");
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['error'] = "Password must be at least 6 characters.";
    header("Location: /public/index.php?page=register");
    exit;
}

if ($password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: /public/index.php?page=register");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['success'] = "Registration successful. Please log in.";
    header("Location: /public/index.php?page=login");
    exit;
} else {
    $_SESSION['error'] = "Email already exists or database error.";
    header("Location: /public/index.php?page=register");
    exit;
}

?>