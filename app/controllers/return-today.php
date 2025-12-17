<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/Borrow.php";
$borrow = new Borrow();

if (empty($_POST['user_id']) ||
    empty($_POST['book_id']) )
{
     redirectBack( "error", "user and book ids are required.");
}
$today = date('Y-m-d');

$user_id = intval($_POST['user_id']);
$book_id = intval($_POST['book_id']);
$return_date = $today;

$returnDateObj = new DateTime($return_date);


//  Fetch active borrow record

$record = $borrow->getActiveReturn($user_id, $book_id);
if (!$record) {
     redirectBack( "error", "No active borrow record found.");
}

$dueDate = new DateTime($record['due_date']);
$dayFine = 10.00;
$fine_amount = 0.00;
$fine_status = "no_fine";


//  Late return-> Calculate fine
if ($returnDateObj > $dueDate) {

    $daysLate = $returnDateObj->diff($dueDate)->days;
    $fine_amount = $daysLate * $dayFine;
    $fine_status = "unpaid";
}

$return_date_final = $returnDateObj->format("Y-m-d");

$res = $borrow->returnBook( $return_date_final, $fine_amount, $fine_status,$user_id,$book_id);
if($res){
    redirectBack( "success", "return processed successfully");
}else{
        redirectBack( "error", "failed to return the book");

}

?>
