<?php
require_once "../../models/Book.php";
$book = new Book();

$q = $_GET['q'] ?? '';
$c = $_GET['category'];

$suggestions = $book->getBookSuggestions($q,$c);

echo json_encode($suggestions);
?>