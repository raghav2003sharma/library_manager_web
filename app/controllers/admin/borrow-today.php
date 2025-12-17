<?php
session_start();
require_once "../../helpers/helpers.php";
require_once "../../models/Borrow.php";
$borrow = new Borrow();

if (empty($_POST['user_id']) ||
    empty($_POST['book_id']) ||
    empty($_POST['borrow_date'])
) {
    redirectBack( "error", "All fields are required.");

}

$user_id = $_POST['user_id'];
$book_id = $_POST['book_id'];
$borrow_date = $_POST['borrow_date'];

// --- Date validation ---
$today = date('Y-m-d');
if ($borrow_date < $today) {
            redirectBack( "error", "Borrow date cannot be in the past.");
}

// --- Check book availability ---

$resultBook = $borrow->getBookStock($book_id);

if ($resultBook->num_rows === 0) {
            redirectBack( "error", "Book not found.");
}
$book = $resultBook->fetch_assoc();
if ($book['stock'] <= 0) {
            redirectBack( "error", "Book is out of stock.");
}

// --- Check previous borrow records ---

$resultSame = $borrow->checkDuplicateBorrow($user_id, $book_id);
if ($resultSame->num_rows > 0) {
            redirectBack( "error", "User already borrowed this book and has not returned it.");
}
// check for fine
$resultFine = $borrow->checkUserFine($user_id);

if ($resultFine->num_rows > 0) {
            redirectBack( "error", "User has unpaid fines.");
}
// check for borrow limit
$countResult = $borrow->borrowCount($user_id);

if ($countResult['total'] >= 5) {
            redirectBack( "error", "User reached the borrow limit of 5 books.");
}

//  Due date (14 days)
$borrowDateObj = new DateTime($borrow_date);
$dueDateObj = clone $borrowDateObj;
$dueDateObj->modify('+14 days');

$due_date = $dueDateObj->format('Y-m-d');
$status = "borrowed";

// Transaction: insert + decrease stock 
$borrow->addBorrowRecord($user_id, $book_id, $borrow_date, $due_date, $status);
header("Location: /admin-home?main-page=dashboard");
exit;
?>
