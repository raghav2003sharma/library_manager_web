<?php 
session_start();
require_once "../../config/db.php";
require_once "../models/Reservation.php";
$reservations = new Reservation();

if(!isset($_SESSION['user_id'])){
    $_SESSION['error'] = "You must be logged in to reserve a book.";
    header("Location: /public/index.php?page=login");
    exit;
}
$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'] ?? '';
$date = $_POST['borrow_date'] ?? '';   
if(empty($book_id) || empty($date)){
    $_SESSION['error'] = "empty fields are not allowed.";
    header("Location: /public/index.php?page=user-home");
    exit;
}
$today = date('Y-m-d');

if ($date < $today) {
    $_SESSION['error'] = "Borrow date cannot be in the past.";
    header("Location: /public/index.php?page=user-home");
    exit;
}

$existingRecord = $reservations->getBorrowRecords($user_id, $book_id, $date);
// change reservation for existing record to expiry or borrowed
$reservations->updateReservationStatus($existingRecord,$user_id,$book_id);

// check for same book
$result_check = $reservations->getActiveReservations($user_id, $book_id);
if($result_check->num_rows > 0){
    $_SESSION['error'] = "You have already reserved or borrowed this book.";
    header("Location: /public/index.php?page=user-home");
    exit;
}


// insert reservation
$isReserved = $reservations->addReservation($user_id, $book_id, $date);
if($isReserved){
    $_SESSION['success'] = "Book reserved successfully. Await approval.";
} else {
    $_SESSION['error'] = "Failed to reserve the book. Please try again.";
}
header("Location: /public/index.php?page=user-home");
exit;



?>