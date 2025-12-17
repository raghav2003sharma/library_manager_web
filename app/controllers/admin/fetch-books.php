<?php
session_start();
require_once "../../models/Book.php";
$book = new Book();
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$sort = $_GET['sort'] ?? "title";
$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';

   
$totalRows = $book->getAllBooksCount($search);
$books = $book->fetchAllBooks( $search,$limit,$offset,$sort, $order);
echo json_encode(["books"=>$books,"totalRows"=>$totalRows,"limit"=>$limit]);

?>