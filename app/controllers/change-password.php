<?php
session_start();
require_once "../../config/db.php";
if (
    empty($_POST['cur-pass']) ||
    empty($_POST['new-pass']) ||
    empty($_POST['conf-pass'])
) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=user-dash");
    exit;
}
$user_id = $_SESSION['user_id'];
$currentPassword = $_POST['cur-pass'];
$newPassword = $_POST['new-pass'];
$confirmPassword = $_POST['conf-pass'];
if (strlen($newPassword) < 6) {
    $_SESSION['error'] = "New password must be at least 6 characters.";
    header("Location: /public/index.php?page=user-dash");
    exit;
}
if ($newPassword !== $confirmPassword) {
    $_SESSION['error'] = "New password and confirm password do not match.";
    header("Location: /public/index.php?page=user-dash");
    exit;
}
$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error'] = "User not found.";
    header("Location: /public/index.php?page=user-dash");
}
$userPassword = $result->fetch_assoc()['password'];
if (!password_verify($currentPassword, $userPassword)) {
    $_SESSION['error'] = "Current password is incorrect.";
    header("Location: /public/index.php?page=user-dash");
    exit;
}
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
$updateStmt->bind_param("si", $hashedPassword, $user_id);
if ($updateStmt->execute()) {
    $_SESSION['success'] = "Password changed successfully.";
    header("Location: /public/index.php?page=user-dash");
    exit;
} else {
    $_SESSION['error'] = "Failed to change password. Please try again.";
    header("Location: /public/index.php?page=user-dash");
    exit;
}
?>