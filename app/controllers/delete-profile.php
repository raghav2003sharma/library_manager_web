<?php
session_start();
require_once "../../config/db.php";

// Must be logged in
if (!isset($_SESSION['user_id'])) {
     header("Location: /public/index.php?page=login");
    exit;
}

$user_id = $_SESSION['user_id'];
// DELETE reservations
$stmt1 = $conn->prepare("DELETE FROM reservations WHERE user_id=?");
$stmt1->bind_param("i", $user_id);
$stmt1->execute();


// DELETE borrow records
$stmt2 = $conn->prepare("DELETE FROM borrow_records WHERE user_id=?");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();


// DELETE contact messages
$stmt3 = $conn->prepare("DELETE FROM contact_messages WHERE user_id=?");
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
// Delete user
$sql = $conn->prepare("DELETE FROM users WHERE user_id=?");
$sql->bind_param("i", $user_id);

if ($sql->execute()) {
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
