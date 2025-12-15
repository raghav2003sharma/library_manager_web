
<?php
session_start();
require_once "../models/Borrow.php";
$borrow = new Borrow();
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total = $borrow->countRecordsToBorrow();
$records = $borrow->recordsToBorrow($limit,$offset);

echo json_encode(["records"=>$records,"totalRows"=>$total,"limit"=>$limit]);
?>