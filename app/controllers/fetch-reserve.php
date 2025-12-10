<?php
require_once "../models/Reservation.php";
$reserve = new Reservation();

$filter = $_GET['filter'] ?? 'pending';
$search = $_GET['q'] ?? '';
$search = "%$search%";
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit  = 5;
$offset = ($page - 1) * $limit;
$sort = $_GET['sort'] ?? "borrow_date";
if($sort === "username") $sort = "u.name";
if($sort === "email") $sort = "u.email";
if($sort === "title") $sort = "b.title";
if($sort === "borrow_date") $sort = "r.borrow_date";
if($sort === "id") $sort = "r.id";
$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';



$totalPages = $reserve->countReservations($filter, $search);

$reservations = $reserve->getReservations($filter, $search, $sort, $order, $offset, $limit);
echo json_encode([
    "reservations" => $reservations,
    "totalRows"   => $totalPages
]);
?>