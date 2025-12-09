<?php
session_start();
require_once "../../config/db.php";
if(
    empty($_POST['fine_email']) ||
    empty($_POST['fine_title']) ||
    empty($_POST['fine_amount'])
){
    $_SESSION['error'] = "All fields are required.";
    header("Location: /public/index.php?page=admin-home&main-page=dashboard");
    exit;
}
$email = $_POST['fine_email'];
$title = $_POST['fine_title'];
$amount = floatval($_POST['fine_amount']);
// Get user ID
$sql1 = "SELECT user_id FROM users WHERE email = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $email);
$stmt1->execute();
$result1 = $stmt1->get_result();
if ($result1->num_rows === 0) {
    $_SESSION['error'] = "User not found.";
    header("Location: /public/index.php?page=admin-home&main-page=fines");
    exit;
}
$user = $result1->fetch_assoc();
$user_id = $user['user_id'];
// Get book ID
$sql2 = "SELECT book_id FROM books WHERE title = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $title);
$stmt2->execute();
$result2 = $stmt2->get_result();
if ($result2->num_rows === 0) {
    $_SESSION['error'] = "Book not found.";
    header("Location: /public/index.php?page=admin-home&main-page=fines");
    exit;
}
$book = $result2->fetch_assoc();
$book_id = $book['book_id'];
// Insert fine payment record
$sql3  = "UPDATE borrow_records 
          SET fine_status = 'paid'
          WHERE user_id = ? AND book_id = ? AND fine_amount = ? AND fine_status = 'unpaid'";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("iid", $user_id, $book_id, $amount);
if ($stmt3->execute()) {
    if($stmt3->affected_rows > 0){
        $_SESSION['success'] = "Fine payment recorded successfully.";
    } else {
        $_SESSION['error'] = "No matching unpaid fine record found.";
    }
    header("Location: /public/index.php?page=admin-home&main-page=fines");
    exit;
} else {
    $_SESSION['error'] = "Error recording fine payment.";
    header("Location: /public/index.php?page=admin-home&main-page=fines");
    exit;
}
?>