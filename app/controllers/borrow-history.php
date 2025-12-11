<?php
session_start();
require_once "../models/Borrow.php";
$borrow = new Borrow();

$sort = $_GET['sort'] ?? "borrow_date";
if($sort === "username") $sort = "u.name";
if($sort === "title") $sort = "b.title";
if($sort === "borrow_date") $sort = "br.borrow_date";
if($sort === "due_date") $sort = "br.due_date";
if($sort === "return_date") $sort = "br.return_date";
if($sort === "status") $sort = "br.status";

$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
 
$totalRows = $borrow->getBorrowHistoryCount($search);


$record = $borrow->getBorrowHistory($search, $sort, $order, $limit, $offset);


echo json_encode(["records"=>$record,"totalRows"=>$totalRows,"limit"=>$limit]);
?>

