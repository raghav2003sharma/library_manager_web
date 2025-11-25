<?php
session_start();
require_once "../../config/db.php";
$user_id = $_SESSION['user_id'];
$sql = "SELECT b.book_id, b.title, b.author,b.category,b.cover_image, br.borrow_date,
    br.due_date FROM borrow_records br INNER JOIN books b on br.book_id = b.book_id
WHERE br.user_id = ? AND br.return_date IS NULL  ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$record = [];
 while ($row = $result->fetch_assoc()) {
        $record[] = $row;
    }
echo json_encode($record);
?>