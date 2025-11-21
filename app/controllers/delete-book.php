<?php
session_start();
require_once "../../config/db.php";

if (empty($_POST['id'])) {
    $_SESSION['error'] = "book ID is required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}
$book_id = $_POST['id'];
$sql = "DELETE FROM books WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
if ($stmt->execute()) {
    $_SESSION['success'] = "Book deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete Book.";
}
header("Location: /public/index.php?page=admin-home&main-page=manage-books");

?>