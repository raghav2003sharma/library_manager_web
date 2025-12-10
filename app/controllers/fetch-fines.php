<?php
require_once "../../config/db.php";

$q     = $_GET['q'] ?? '';
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$limit  = 5;
$offset = ($page - 1) * $limit;

$searchSQL = "";
$params    = [];
$types     = "";
$sort = $_GET['sort'] ?? "borrow_date";
if($sort === "username") $sort = "u.name";
if($sort === "email") $sort = "u.email";

if($sort === "title") $sort = "b.title";
if($sort === "borrow_date") $sort = "f.borrow_date";
if($sort === "return_date") $sort = "f.return_date";
if($sort === "author") $sort = "b.author";
if($sort === "fine_amount") $sort = "f.fine_amount";


$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';
if (!empty($q)) {
    $searchSQL = " AND (
        u.username LIKE CONCAT('%', ?, '%') OR
        u.email    LIKE CONCAT('%', ?, '%') OR
        b.title    LIKE CONCAT('%', ?, '%') OR
        b.author   LIKE CONCAT('%', ?, '%')
    )";
    $params = [$q, $q, $q, $q];
    $types  = "ssss";
}
$countQuery = "
    SELECT COUNT(*) AS total
    FROM borrow_records br
    JOIN users u ON u.user_id = br.user_id
    JOIN books b ON b.book_id = br.book_id
    WHERE status='returned' AND fine_status ='unpaid' $searchSQL
";

$stmt = $conn->prepare($countQuery);
if (!empty($q)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];

$sql = "
    SELECT 
        f.fine_amount,
        f.fine_status,
        f.created_at,
        b.title,
        b.author,
        f.borrow_date,
        f.return_date,
        u.name,
        u.email
    FROM borrow_records f
    JOIN users u ON u.user_id = f.user_id
    JOIN books b ON b.book_id = f.book_id
    WHERE status='returned' AND fine_status ='unpaid' $searchSQL
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset
";

$stmt2 = $conn->prepare($sql);
if (!empty($q)) $stmt2->bind_param($types, ...$params);
$stmt2->execute();
$result = $stmt2->get_result();

$fines = [];

while ($row = $result->fetch_assoc()) {
    $fines[] = [
        "username"    => $row['name'],
        "email"       => $row['email'],
        "title"       => $row['title'],
        "author"      => $row['author'],
        "borrow_date" => $row['borrow_date'],
        "return_date" => $row['return_date'],
        "amount"      => $row['fine_amount'],
        "status"      => $row['fine_status'],
        "created_at"  => $row['created_at']
    ];
}


echo json_encode([
    "fines"     => $fines,
    "totalRows" => $totalRows
]);

?>
