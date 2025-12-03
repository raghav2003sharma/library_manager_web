<?php
session_start();
require_once "../../config/db.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id']; // logged-in user
$filter = $_GET['filter'] ?? 'pending';
$search = $_GET['q'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$searchLike = "%" . $search . "%";

// ----------------------
// COUNT TOTAL RECORDS
// ----------------------
$countSql = "SELECT COUNT(*) AS total 
             FROM reservations r
             JOIN books b ON r.book_id = b.book_id
             WHERE r.user_id = ? 
               AND r.status = ? 
               AND b.title LIKE ?";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("iss", $user_id, $filter, $searchLike);
$countStmt->execute();
$total = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// ----------------------
// FETCH CURRENT PAGE DATA
// ----------------------
$sql = "SELECT r.id, r.status, r.borrow_date, b.title, b.cover_image
        FROM reservations r
        JOIN books b ON r.book_id = b.book_id
        WHERE r.user_id = ? 
          AND r.status = ? 
          AND b.title LIKE ?
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issii", $user_id, $filter, $searchLike, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = [
        "id" => $row["id"],
        "status" => $row["status"],
        "title" => $row["title"],
        "cover_image" => $row["cover_image"],
        "borrow_date" => $row["borrow_date"] ?? '',
        "due_date" => $row["due_date"] ?? ''
    ];
}

echo json_encode([
    "reservations" => $reservations,
    "totalPages" => $totalPages
]);
?>
