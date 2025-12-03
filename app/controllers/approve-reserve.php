<?php
session_start();
require_once "../../config/db.php";
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
error_log($id);
$sql = "UPDATE reservations SET status = 'approved' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
if($stmt->execute()){
   
    echo json_encode([
        "success" => true,
        "message" => "Reservation approved"
    ]);
    exit;
} else {
 echo json_encode([
        "success" => false,
        "message" => "Database error"
    ]);
    exit;}

?>