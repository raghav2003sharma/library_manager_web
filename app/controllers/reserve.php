<?php 
session_start();
require_once "../../config/db.php";
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
$sql_check_record = "SELECT id FROM borrow_records 
                     WHERE user_id = ? 
                       AND book_id = ? 
                       AND borrow_date = ?"; 
$stmt_check_record = $conn->prepare($sql_check_record);
$stmt_check_record->bind_param("iis", $user_id, $book_id, $date);
$stmt_check_record->execute();
$result = $stmt_check_record->get_result();

if ($result->num_rows > 0) {
 $sql6 ="UPDATE reservations SET status='borrowed' WHERE user_id=? AND book_id=? AND status='approved'";// update reservation status
    $stmt6 = $conn->prepare($sql6);
    $stmt6->bind_param("ii", $user_id, $book_id);
    $stmt6->execute();
}
// change the status to expiry if the reservation date has passed
$sql_expire = "UPDATE reservations SET status='expired' WHERE status='approved' AND borrow_date < CURDATE() AND user_id=? AND book_id=?";
$stmt_expire = $conn->prepare($sql_expire);
$stmt_expire->bind_param("ii", $user_id, $book_id);
$stmt_expire->execute();
// check for same book
$sql_check = "SELECT * FROM reservations WHERE user_id=? AND book_id=? AND status IN ('pending','approved','borrowed')";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $book_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if($result_check->num_rows > 0){
    $_SESSION['error'] = "You have already reserved or borrowed this book.";
    header("Location: /public/index.php?page=user-home");
    exit;
}
// insert reservation

$sql = "INSERT INTO reservations (user_id, book_id, status,borrow_date) VALUES (?, ?, 'pending',?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $book_id, $date);
if($stmt->execute()){
    $_SESSION['success'] = "Book reserved successfully. Await approval.";
} else {
    $_SESSION['error'] = "Failed to reserve the book. Please try again.";
}
header("Location: /public/index.php?page=user-home");
exit;



?>