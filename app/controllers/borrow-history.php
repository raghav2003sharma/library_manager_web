<?php
session_start();
require_once "../../config/db.php";
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$sql = "SELECT u.name,b.title, br.borrow_date,br.status,br.return_date,
    br.due_date FROM borrow_records br INNER JOIN users u on br.user_id = u.user_id
INNER JOIN books b ON br.book_id = b.book_id WHERE u.name LIKE ? OR b.title LIKE ? ORDER BY br.borrow_date DESC; ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();
$record = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $record[] = $row;
    }
}

echo json_encode($record);
?>

