<?php 
session_start();
require_once "../helpers/helpers.php";
require_once "../models/Reservation.php";
$reservations = new Reservation();
if(!isset($_SESSION['user_id'])){
    redirect("/public/index.php?page=login","error","You must be logged in first");

}
$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'] ?? '';
$date = $_POST['borrow_date'] ?? '';   
$today = date('Y-m-d');

if ($date < $today) {
    redirectBack( "error", "Borrow date cannot be in the past.");

    
}
if(empty($book_id) || empty($date)){
    redirectBack( "error", "All fields are required.");
}
$updated = $reservations->updateReservation($date,$book_id,$user_id);
if($updated){
    redirectBack( "success", "Date updated successfully. Await approval.");

} else {
    redirectBack( "error", "Failed to Edit the borrow date. Please try again.");

}
