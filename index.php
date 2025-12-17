<?php
session_start();
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$page = $path === '' ? 'user-home' : $path;

$publicPages = [
    'register' => __DIR__ . '/app/views/register.php',
    'login'    => __DIR__ . '/app/views/login.php',
    'contact'  => __DIR__ . '/app/views/contact.php',
    'about'    => __DIR__ . '/app/views/aboutus.php',
];

$userPages = [
    'user-home' => __DIR__ . '/app/views/user-dash.php',
    'view-book' => __DIR__ . '/app/views/view-book.php',
    'borrowed'  => __DIR__ . '/app/views/borrowed.php',
    'reserves'  => __DIR__ . '/app/views/user-reservations.php',
];

$adminPages = [
    'admin-home' => __DIR__ . '/app/views/admin-dash.php',
];

if($page === 'logout'){
   require_once __DIR__ . '/app/controllers/auth/logout.php';
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
require_once __DIR__ . '/app/views/404.php';
exit;
?>