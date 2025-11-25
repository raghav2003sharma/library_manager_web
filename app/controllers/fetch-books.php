<?php
session_start();
require_once "../../config/db.php";
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$sql = "SELECT book_id, title, author, category, stock,cover_image,created_at 
        FROM books
        WHERE title LIKE ?
           OR author LIKE ?
           OR category LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
echo json_encode($books);

?>