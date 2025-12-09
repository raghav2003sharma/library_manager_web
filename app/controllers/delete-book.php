<?php
session_start();
require_once "../models/Book.php";
$book = new Book();
if (empty($_POST['id'])) {
    $_SESSION['error'] = "book ID is required.";
    header("Location: /public/index.php?page=admin-home&main-page=manage-books");
    exit;
}
$book_id = $_POST['id'];
$isDeleted = $book->deleteBook($book_id);
if ($isDeleted) {
    $_SESSION['success'] = "Book deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete Book.";
}
header("Location: /public/index.php?page=admin-home&main-page=manage-books");

?>