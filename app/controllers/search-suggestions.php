<?php
require_once "../../config/db.php";

$q = $_GET['q'] ?? '';

$sql = "SELECT title FROM books WHERE title LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$search = "%$q%";
$stmt->bind_param("s", $search);
$stmt->execute();

$result = $stmt->get_result();
$suggestions = [];

while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row;
}

echo json_encode($suggestions);
?>