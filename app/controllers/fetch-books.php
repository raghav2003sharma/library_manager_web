<?php
session_start();
require_once "../../config/db.php";
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
    $total = $conn->prepare("Select count(*) as total from books WHERE title LIKE ?
           OR author LIKE ?");
    $total->bind_param("ss", $search, $search);
    $total->execute();
    $res = $total->get_result();
    $totalRows = $res->fetch_assoc()['total'];
$sql = "SELECT book_id, title, author,description, category, stock,cover_image,created_at 
        FROM books
        WHERE title LIKE ?
           OR author LIKE ?
           OR category LIKE ? limit ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $search, $search, $search,$limit,$offset);
$stmt->execute();
$result = $stmt->get_result();
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
echo json_encode(["books"=>$books,"totalRows"=>$totalRows]);

?>