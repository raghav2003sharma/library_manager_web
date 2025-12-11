<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/User.php";
require_once "../models/Book.php";
require_once "../models/Borrow.php";
$borrow = new Borrow();
$books = new Book();
$users = new User();

if(
    empty($_POST['fine_email']) ||
    empty($_POST['fine_title']) ||
    empty($_POST['fine_amount'])
){
        redirectBack( "error", "All fields are required.");

}
$email = $_POST['fine_email'];
$title = $_POST['fine_title'];
$amount = floatval($_POST['fine_amount']);
// Get user ID

$result1 = $users->getUserByEmail($email);
if ($result1->num_rows === 0) {
            redirectBack( "error", "User not found");

}
$user = $result1->fetch_assoc();
$user_id = $user['user_id'];
// Get book ID

$result2 = $books->getBookByTitle($title);
if ($result2->num_rows === 0) {
             redirectBack( "error", "Book not found.");

}
$book = $result2->fetch_assoc();
$book_id = $book['book_id'];

// Insert fine payment record
$result3 = $borrow->payFine($user_id, $book_id, $amount);
    if($result3 > 0){
         redirectBack( "success", "Fine payment recorded successfully.");

    } else {
         redirectBack( "error", "Error in updating fine record.");

    }
    


?>