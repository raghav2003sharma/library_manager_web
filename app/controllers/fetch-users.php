<?php
session_start();
require_once "../../config/db.php";
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
    $total = $conn->prepare("Select count(*) as total from users WHERE name LIKE ?
           OR email LIKE ?
           OR role LIKE ?");
    $total->bind_param("sss", $search, $search, $search);
    $total->execute();
    $res = $total->get_result();
    $totalRows = $res->fetch_assoc()['total'];
    error_log($totalRows);
$sql = "SELECT user_id, name, email, role, created_at 
        FROM users
        WHERE name LIKE ?
           OR email LIKE ?
           OR role LIKE ?  limit ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $search, $search, $search,$limit,$offset);
$stmt->execute();
$result = $stmt->get_result();
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;  
    }
}
echo json_encode(["users"=>$users,"totalRows"=>$totalRows]);

?>