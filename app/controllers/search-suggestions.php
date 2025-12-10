<?php
require_once "../models/Book.php";
$book = new Book();

$q = $_GET['q'] ?? '';

$suggestions = $book->getBookSuggestions($q);

echo json_encode($suggestions);
?>