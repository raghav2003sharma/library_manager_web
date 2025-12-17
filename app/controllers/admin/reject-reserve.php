<?php
session_start();
require_once "../../models/Reservation.php";
$reservations = new Reservation();
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);

if(!isset($input['id'])){
     echo json_encode([
        "success" => false,
        "message" => "ID is required"
    ]);
    exit;
}
$id = $input['id'];
$isRejected = $reservations->rejectReservation($id);
if($isRejected){
     echo json_encode([
        "success" => true,
        "message" => "Reservation rejected"
    ]);
    exit;
} else {
    echo json_encode([
        "success" => false,
        "message" => "Database error"
    ]);
    exit;
}
?>