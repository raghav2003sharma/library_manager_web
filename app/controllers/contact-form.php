<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user_id'])){
   echo json_encode(["success" => false, "message" => "You must be logged in to contact us."]);
    exit;
}
$user_id = $_SESSION['user_id'];

// Validate required fields
if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
     echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
    exit;
}

// Sanitize inputs
$name    = trim($_POST['name']);
$email   = trim($_POST['email']);
$message = trim($_POST['message']);

// Insert into database
$sql = "INSERT INTO contact_messages (user_id, name, email, message)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $user_id, $name, $email, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Message sent successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error."]);
}
exit;

?>