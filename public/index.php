<?php
session_start();
$page = $_GET['page'] ?? 'user-home';

$publicPages = [
    'register'   => '../app/views/register.php',
    'login'      => '../app/views/login.php',
    'contact'    => '../app/views/contact.php',
    'about'      => '../app/views/aboutus.php',
];
$userPages = [
    'user-home'  => '../app/views/user-dash.php',
    'view-book'  => '../app/views/view-book.php',
    'borrowed'   => '../app/views/borrowed.php',
    'reserves'   => '../app/views/user-reservations.php',
];
$adminPages = [
    'admin-home' => '../app/views/admin-dash.php',
];

if($page === 'logout'){
    require_once "../app/controllers/auth/logout.php";
    exit;
}
if (array_key_exists($page, $publicPages)) {
    require_once $publicPages[$page];
    exit;
}
if (array_key_exists($page, $adminPages)) {
    require_once $adminPages[$page];
    exit;
}
if (array_key_exists($page, $userPages)){
    require_once $userPages[$page];
    exit;
}
http_response_code(404);
require_once "../app/views/404.php";
exit;
?>