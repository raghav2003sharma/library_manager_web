<?php
session_start();
require_once "../../helpers/helpers.php";
require_once "../../models/Book.php";
$book = new Book();
if (empty($_POST['id'])) {
             redirectBack( "error", "book ID is required.");

}
$book_id = $_POST['id'];
$isDeleted = $book->deleteBook($book_id);
if ($isDeleted) {
                 redirectBack( "success", "Book deleted successfully.");
} else {
         redirectBack( "error", "Failed to delete Book.");
}

?>