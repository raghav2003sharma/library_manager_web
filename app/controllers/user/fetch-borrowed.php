<?php
session_start();
require_once "../../models/Borrow.php";
$borrow = new Borrow();

$user_id = $_SESSION['user_id'];
$records = $borrow->getUserBorrowed($user_id);
echo json_encode($records);
?>