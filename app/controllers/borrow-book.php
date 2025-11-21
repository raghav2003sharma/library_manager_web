<?php
session_start();
require_once "../../config/db.php";
if(empty($_POST['b-email']) ||
   empty($_POST['b-title']) ||
   empty($_POST['b-date'])
){
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
$email = $_POST['b-email'];
$title = $_POST['b-title'];
$date = $_POST['b-date'];


// check if user is registered
$sql1 = "SELECT user_id FROM users WHERE email = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $email);
$stmt1->execute();
$result1 = $stmt1->get_result();
if ($result1->num_rows === 0) {
    $_SESSION['error'] = "User not found.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
$user = $result1->fetch_assoc();
$user_id = $user['user_id'];
// check if book exists and is in stock
$sql2 = "SELECT book_id, stock FROM books WHERE title = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $title);
$stmt2->execute();
$result2 = $stmt2->get_result();
if ($result2->num_rows === 0) {
    $_SESSION['error'] = "Book not found.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
$book = $result2->fetch_assoc();
$book_id = $book['book_id'];
$stock = $book['stock'];
if ($stock <= 0) {
    $_SESSION['error'] = "Book is out of stock.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
// check if user has already borrowed this book and not returned yet and check for fine status
$sql3 = "SELECT * FROM borrow_records WHERE user_id = ? AND book_id = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("ii", $user_id, $book_id);
$stmt3->execute();
$result3 = $stmt3->get_result();
$borrowLimit = 0;
while($row = $result3->fetch_assoc()){
    $borrowLimit++;
    if($row['return_date'] === null){
        $_SESSION['error'] = "User has already borrowed this book and not returned yet.";
        header("Location: /public/index.php?page=admin-home&main-page=dashboard");
        exit;
    }
    elseif($row['fine_status'] === 'unpaid'){
        $_SESSION['error'] = "User has unpaid fines. Cannot borrow new books.";
        header("Location: /public/index.php?page=admin-home&main-page=dashboard");
        exit;
    }
}
if($borrowLimit > 5){// set borrow limit to 5 books
    $_SESSION['error'] = "User has reached the borrow limit of 5 books.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
//calculate due date based on borrow date and 14-day policy
$borrowDate = new DateTime($date);
$dueDate = clone $borrowDate;
$dueDate->modify('+14 days');
$borrow_date = $borrowDate->format('Y-m-d');
$due_date = $dueDate->format('Y-m-d');
$status = "borrowed";
// insert borrow record and change status to borrowed and decrease stock in books table in a transaction
$conn->begin_transaction();
try {
    // Insert borrow record
    $sql4 = "INSERT INTO borrow_records 
             (user_id, book_id, borrow_date, due_date, status) 
             VALUES (?, ?, ?, ?, ?)";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("iisss", $user_id, $book_id, $borrow_date, $due_date, $status);
    $stmt4->execute();

    // Update stock
    $sql5 = "UPDATE books SET stock = stock - 1 WHERE book_id = ?";
    $stmt5 = $conn->prepare($sql5);
    $stmt5->bind_param("i", $book_id);
    $stmt5->execute();

    $conn->commit();
    $_SESSION['success'] = "Borrow record added successfully.";

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Failed to add borrow record. Please try again.";
}
header("Location: /public/index.php?page=admin-home&main-page=dashboard");  
exit;



?>

