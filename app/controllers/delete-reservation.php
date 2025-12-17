<?php 
session_start();
require_once "../helpers/helpers.php";
require_once "../models/Reservation.php";
$reservations = new Reservation();
if(!isset($_SESSION['user_id'])){
    redirect("/login","error","You must be logged in first");

}
$user_id = $_SESSION['user_id'];
$book_id = $_POST['id'] ?? '';
$status = $_POST['status'];

if(empty($book_id) || empty($status)){
    redirectBack( "error", "empty book id or status");
}
$isDeleted = $reservations->deleteReservation($user_id, $book_id,$status);
if($isDeleted){
     redirectBack( "success", "Reservation deleted successfully");

} else {
        redirectBack( "error", "Failed to delete reservation. Please try again.");

}
