<?php 
session_start();
require_once "../models/Reservation.php";
$reservations = new Reservation();
if(!isset($_SESSION['user_id'])){
    $_SESSION['error'] = "You must be logged in !";
    header("Location: /public/index.php?page=login");
    exit;
}
$user_id = $_SESSION['user_id'];
$book_id = $_POST['id'] ?? '';
$status = $_POST['status'];

if(empty($book_id) || empty($status)){
    $_SESSION['error'] = "empty book id or status";
    header("Location: /public/index.php?page=reserves");
    exit;
}
$isDeleted = $reservations->deleteReservation($user_id, $book_id,$status);
if($isDeleted){
    $_SESSION['success'] = "Reservation deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete reservation. Please try again.";
}
header("Location: /public/index.php?page=reserves");
exit;