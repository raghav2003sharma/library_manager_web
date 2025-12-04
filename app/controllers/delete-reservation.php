<?php 
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user_id'])){
    $_SESSION['error'] = "You must be logged in to reserve a book.";
    header("Location: /public/index.php?page=login");
    exit;
}
$user_id = $_SESSION['user_id'];
$book_id = $_POST['id'] ?? '';
$status = $_POST['status'];

if(empty($book_id) || empty($status)){
    $_SESSION['error'] = "empty book id or status";
    header("Location: /public/index.php?page=user-home");
    exit;
}
$sql = "DELETE FROM reservations WHERE user_id = ? AND book_id = ? AND status =?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $book_id,$status);
$stmt->execute();
if($stmt->execute()){
    $_SESSION['success'] = "Date updated successfully. Await approval.";
} else {
    $_SESSION['error'] = "Failed to Edit the borrow date. Please try again.";
}
header("Location: /public/index.php?page=user-home");
exit;