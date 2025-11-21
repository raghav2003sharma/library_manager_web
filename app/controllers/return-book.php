<?php
session_start();
require_once "../../config/db.php";
if(
    empty($_POST['r-email']) ||
    empty($_POST['r-title']) ||
    empty($_POST['r-date'])
){
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
$email = $_POST['r-email'];
$title = $_POST['r-title'];
$date = $_POST['r-date'];
// return logic
// if return date > due date then calculate fine and change finestatus to unpaid.....
// change status to returned and incres stock by 1
$returnDate = new DateTime($date);
$sql1 = "SELECT u.user_id,b.book_id, br.borrow_date,
    br.due_date,
    br.return_date,
    br.status,
    br.fine_status FROM borrow_records br INNER JOIN users u on br.user_id = u.user_id
INNER JOIN books b ON br.book_id = b.book_id
WHERE u.email = ?
  AND b.title = ?
  AND br.return_date IS NULL; ";
  $stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ss", $email, $title);
$stmt1->execute();
$result = $stmt1->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error'] = "No active borrow record found for this user and book.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$book_id = $row['book_id'];
$dueDate = new DateTime($row['due_date']);
$returnDate = new DateTime($date);
$return_date = $returnDate->format('Y-m-d');
$dayFine = 10.00;
$fine_status = "no_fine";
$fine_amount = 0.00;
if ($returnDate > $dueDate) {
    // overdue calculate fine
        $daysLate = $returnDate->diff($dueDate)->days;  
        $fine_amount = $daysLate * $dayFine;
        $fine_status = "unpaid";

}
$conn->begin_transaction();
try{
    $sql2 = "UPDATE borrow_records 
    SET return_date=?, status='returned',fine_amount=?, fine_status=? WHERE user_id = ? AND book_id = ? AND return_date is NULL ";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("sdsii", $return_date, $fine_amount, $fine_status, $user_id, $book_id);
$stmt2->execute();
$stmt3 = $conn->prepare("UPDATE books SET stock = stock + 1 WHERE book_id = ?");
$stmt3->bind_param("i",$book_id);
$stmt3->execute();
   $conn->commit();
    $_SESSION['success'] = "Return record added successfully.";

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Failed to add return record. Please try again.";
}
header("Location: /public/index.php?page=admin-home&main-page=dashboard");  
exit;