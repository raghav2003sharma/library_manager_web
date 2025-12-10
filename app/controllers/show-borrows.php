<?php
session_start();
require_once "../models/Borrow.php";
$borrow = new Borrow();

$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$sort = $_GET['sort'] ?? "borrow_date";
if($sort === "username") $sort = "u.name";
if($sort === "title") $sort = "b.title";
if($sort === "borrow_date") $sort = "br.borrow_date";
if($sort === "due_date") $sort = "br.due_date";
if($sort === "status") $sort = "br.status";

$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';
 
$totalRows = $borrow->getActiveBorrowCount($search);

$records = $borrow->getActiveBorrowRecords($search, $sort, $order, $limit, $offset);


echo json_encode(["records"=>$records,"totalRows"=>$totalRows]);
?>



