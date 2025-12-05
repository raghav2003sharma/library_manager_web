<?php 
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user_id'])){
    $_SESSION['error'] = "You must be logged in.";
    header("Location: /public/index.php?page=login");
    exit;
}
$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'] ?? '';
$date = $_POST['borrow_date'] ?? '';   
if(empty($book_id) || empty($date)){
    $_SESSION['error'] = "empty fields are not allowed.";
    header("Location: /public/index.php?page=reserves");
    exit;
}
$sql = "UPDATE reservations set borrow_date = ? WHERE book_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $date,$book_id,$user_id);
if($stmt->execute()){
    $_SESSION['success'] = "Date updated successfully. Await approval.";
} else {
    $_SESSION['error'] = "Failed to Edit the borrow date. Please try again.";
}
header("Location: /public/index.php?page=reserves");
exit;