
<?php
session_start();
require_once "../models/Borrow.php";
$borrow = new Borrow();

$approved_today = $borrow->recordsToBorrow();
$result = [];
while ($row = $approved_today->fetch_assoc()) {
    $result[] = $row;
}

echo json_encode($result);
?>