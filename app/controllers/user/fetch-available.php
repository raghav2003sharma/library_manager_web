<?php
session_start();
require_once "../../models/Book.php";
$book = new Book();
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit =10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$category = isset($_GET['category']) ? $_GET['category'] : null;
$books = $book->fetchAvailableBooks($category,$search, $limit, $offset);
echo json_encode($books);