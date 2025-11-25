<?php
session_start();
require_once "../../config/db.php";
$search = $_GET['q'] ?? ""; 
$search = "%$search%";

$sql = "SELECT user_id, name, email, role, created_at 
        FROM users
        WHERE name LIKE ?
           OR email LIKE ?
           OR role LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
echo json_encode($users);

?>