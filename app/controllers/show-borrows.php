<?php
session_start();
require_once "../../config/db.php";
$search = $_GET['q'] ?? ""; 
$search = "%$search%";
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
 $total = $conn->prepare("Select count(*) as total FROM borrow_records br INNER JOIN users u on br.user_id = u.user_id
INNER JOIN books b ON br.book_id = b.book_id
WHERE  br.return_date IS NULL AND (u.name LIKE ? OR b.title LIKE ? )");
    $total->bind_param("ss", $search, $search);
    $total->execute();
    $res = $total->get_result();
    $totalRows = $res->fetch_assoc()['total'];
$sql = "SELECT u.user_id,u.name,b.book_id,b.title, br.borrow_date,
    br.due_date FROM borrow_records br INNER JOIN users u on br.user_id = u.user_id
INNER JOIN books b ON br.book_id = b.book_id
WHERE  br.return_date IS NULL AND (u.name LIKE ? OR b.title LIKE ? ) limit ? offset ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $search, $search,$limit,$offset);
$stmt->execute();
$result = $stmt->get_result();
$record = [];
        $today = new DateTime();             

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
$dueDate = new DateTime($row['due_date']);  
if($dueDate === $today){
    $row['status'] = "due today" ;
}
elseif($today > $dueDate){
    $row['status'] = "over due";
}else{
        $row['status'] = "borrowed";
}
        $record[] = $row;
    }
}

echo json_encode(["records"=>$record,"totalRows"=>$totalRows]);
?>



