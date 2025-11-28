<?php
session_start();
$page = $_GET['page'] ?? 'user-home';

if ($page === 'register') {
    require_once "../app/views/register.php";
    exit;
}

if ($page === 'login') {
    require_once "../app/views/login.php";
    exit;
}
if ($page === 'admin-home') {
    require_once "../app/views/admin-dash.php";
    exit;
}

if ($page === 'user-home') {
    require_once "../app/views/user-dash.php";
    exit;
}
if($page === 'logout'){
    require_once "../app/controllers/logout.php";
    exit;
}

if (isset($_SESSION['user_id'])) {
    if($_SESSION['role'] === 'admin'){
        require_once "../app/views/admin-dash.php";
        exit;
    } else {
        require_once "../app/views/user-dash.php";
        exit;

    }
}

require_once "../app/views/user-dash.php";

?>