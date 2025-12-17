<?php 
session_start();
require_once "../../helpers/helpers.php";
require_once "../../models/Reservation.php";
$reservations = new Reservation();

if(!isset($_SESSION['user_id'])){
       redirect("/login","error","You must be logged in to reserve a book");

}
$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'] ?? '';
$date = $_POST['borrow_date'] ?? '';   
if(empty($book_id) || empty($date)){
    redirectBack( "error", "empty fields are not allowed.");
}
$today = date('Y-m-d');

if ($date < $today) {
    redirectBack( "error", "Borrow date cannot be in the past.");
}
$reservationNumber = $reservations->countPendingReservations($user_id);
if($reservationNumber >=5){
        redirectBack( "error", "Your reservation limit of 5 has been reached !");

}
$existingRecord = $reservations->getBorrowRecords($user_id, $book_id);
// change reservation for existing record to expiry or borrowed
$reservations->updateReservationStatus($existingRecord,$user_id,$book_id);
if($existingRecord && $existingRecord->num_rows > 0){
        redirect("/user-home", "error", "You have currently borrowed this book.");

}

// check for same book
$result_check = $reservations->getActiveReservations($user_id, $book_id);
if($result_check->num_rows > 0){
    redirect("/user-home", "error", "You have already reserved this book.");

}


// insert reservation
$isReserved = $reservations->addReservation($user_id, $book_id, $date);
if($isReserved){
    redirect("/user-home", "success", "Book reserved successfully. Await approval.");

} else {
    redirect("/user-home", "error", "Failed to reserve the book. Please try again.");

}




?>