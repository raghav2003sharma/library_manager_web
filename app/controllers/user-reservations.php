<?php
session_start();
require_once "../models/Reservation.php";
$reserves = new Reservation();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id']; 
$filter = $_GET['filter'] ?? 'pending';
$search = $_GET['q'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$searchLike = "%" . $search . "%";


$totalRows = $reserves->countUserReservations($user_id, $filter, $searchLike);

$reservations = $reserves->getUserReservations($user_id, $filter, $searchLike, $offset, $limit);

echo json_encode([
    "reservations" => $reservations,
    "totalRows" => $totalRows
]);
?>
