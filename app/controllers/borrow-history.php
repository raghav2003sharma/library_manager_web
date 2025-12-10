<?php
session_start();
require_once "../../config/db.php";
$sort = $_GET['sort'] ?? "borrow_date";
if($sort === "username") $sort = "u.name";
if($sort === "title") $sort = "b.title";
if($sort === "borrow_date") $sort = "br.borrow_date";
if($sort === "due_date") $sort = "br.due_date";
if($sort === "return_date") $sort = "br.return_date";
if($sort === "status") $sort = "br.status";

$order = ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
 $total = $conn->prepare("Select count(*) as total FROM borrow_records br INNER JOIN users u on br.user_id = u.user_id
                INNER JOIN books b ON br.book_id = b.book_id WHERE u.name LIKE ? OR b.title LIKE ?");
    $total->bind_param("ss", $search, $search);
    $total->execute();
    $res = $total->get_result();
    $totalRows = $res->fetch_assoc()['total'];
$sql = "SELECT u.name,b.title, br.borrow_date,br.status,br.return_date,
    br.due_date FROM borrow_records br INNER JOIN users u on br.user_id = u.user_id
INNER JOIN books b ON br.book_id = b.book_id WHERE u.name LIKE ? OR b.title LIKE ? ORDER BY $sort $order limit ? offset ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $search, $search,$limit,$offset);
$stmt->execute();
$result = $stmt->get_result();
$record = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $record[] = $row;
    }
}

echo json_encode(["records"=>$record,"totalRows"=>$totalRows]);
?>

