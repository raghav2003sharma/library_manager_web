<?php
session_start();
require_once "../../config/db.php";

$result = $conn->query("SELECT book_id,title,author,category,stock,created_at FROM books");
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
echo json_encode($books);

?>