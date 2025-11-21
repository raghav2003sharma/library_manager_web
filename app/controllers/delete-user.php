<?php
session_start();
require_once "../../config/db.php";

if (empty($_POST['id'])) {
    $_SESSION['error'] = "User ID is required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-users");
    exit;
}
$user_id = $_POST['id'];
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    $_SESSION['success'] = "User deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete user.";
}
header("Location: /public/index.php?page=admin-home&main-page=manage-users");

?>