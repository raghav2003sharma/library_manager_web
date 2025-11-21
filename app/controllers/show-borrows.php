<?php
session_start();
require_once "../../config/db.php";

$sql = "SELECT u.name,b.title, br.borrow_date,
    br.due_date FROM borrow_records br INNER JOIN users u on br.user_id = u.user_id
INNER JOIN books b ON br.book_id = b.book_id
WHERE  br.return_date IS NULL; ";
$result = $conn->query($sql);
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

echo json_encode($record);
?>



