
<?php
session_start();
require_once "../../config/db.php";


$approved_today = $conn->query("
    SELECT r.*, u.name, u.email, b.title,b.author
    FROM reservations r
    JOIN users u ON r.user_id = u.user_id
    JOIN books b ON r.book_id = b.book_id
    WHERE r.status = 'approved'
    AND r.borrow_date = CURDATE()
");
$result = [];
while ($row = $approved_today->fetch_assoc()) {
    $result[] = $row;
}

echo json_encode($result);
?>