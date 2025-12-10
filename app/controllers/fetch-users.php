<?php
session_start();
require_once "../models/User.php";
$user = new User();
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$sort = $_GET['sort'] ?? "name";
$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';
$totalRows = $user->getUserCount($search);

$users = $user->fetchAllUsers($search,$limit,$offset,$sort, $order);
echo json_encode(["users"=>$users,"totalRows"=>$totalRows]);

?>