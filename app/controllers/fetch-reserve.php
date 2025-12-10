<?php
require_once "../../config/db.php";

$filter = $_GET['filter'] ?? 'pending';
$search = $_GET['q'] ?? '';
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit  = 5;
$offset = ($page - 1) * $limit;
$sort = $_GET['sort'] ?? "borrow_date";
if($sort === "username") $sort = "u.name";
if($sort === "email") $sort = "u.email";
if($sort === "title") $sort = "b.title";
if($sort === "borrow_date") $sort = "r.borrow_date";
if($sort === "id") $sort = "r.id";
$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';

$countSql = "SELECT COUNT(*) AS total
             FROM reservations r
             JOIN users u ON r.user_id = u.user_id
             JOIN books b ON r.book_id = b.book_id
             WHERE r.status = ?
             AND (u.name LIKE ? OR u.email LIKE ? OR b.title LIKE ?)";

$countStmt = $conn->prepare($countSql);

$searchLike = "%" . $search . "%";
$countStmt->bind_param("ssss", $filter, $searchLike, $searchLike, $searchLike);
$countStmt->execute();
$total = $countStmt->get_result()->fetch_assoc()['total'];

$totalPages = ceil($total / $limit);

$sql = "SELECT r.id , r.status,r.borrow_date, u.name AS username, u.email, 
               b.title, b.cover_image
        FROM reservations r
        JOIN users u ON r.user_id = u.user_id
        JOIN books b ON r.book_id = b.book_id
        WHERE r.status = ?
        AND (u.name LIKE ? OR u.email LIKE ? OR b.title LIKE ?)
        ORDER BY $sort $order
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssii", $filter, $searchLike, $searchLike, $searchLike, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();
$reservations = [];

while ($row = $result->fetch_assoc()) {
    $reservations[] = [
        "id"          => $row["id"],
        "status"      => $row["status"],
        "username"    => $row["username"],
        "email"       => $row["email"],
        "title"       => $row["title"],
        "cover_image" => $row["cover_image"],
        "borrow_date"=>$row["borrow_date"]
    ];
}
echo json_encode([
    "reservations" => $reservations,
    "totalRows"   => $totalPages
]);
?>