<?php
session_start();
$page = $_GET['page'] ?? 'login';

if ($page === 'register') {
    require_once "../app/views/register.php";
    exit;
}

if ($page === 'login') {
    require_once "../app/views/login.php";
    exit;
}

// if (isset($_SESSION['user_id'])) {
//     if($_SESSION['role'] === 'admin'){
//         header("Location: ../app/views/home.php");
//         exit;
//     } else {
//         header("Location: ../app/views/dashboard.php");
//         exit;
//     }
// }

require_once "../app/views/login.php";

?>