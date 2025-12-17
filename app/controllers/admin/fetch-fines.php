<?php
require_once "../../models/Borrow.php";
$borrow = new Borrow();

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

$totalRows = $borrow->countFines($searchSQL,$types,$params);

$fines = $borrow->getAllFines($searchSQL,$types,$params,$limit,$offset,$sort,$order);

echo json_encode([
    "fines"     => $fines,
    "totalRows" => $totalRows
]);

?>
