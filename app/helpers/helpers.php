<?php
function redirect($url, $type = null, $message = null) {
    if ($type && $message) {
        $_SESSION[$type] = $message;
    }
    header("Location: $url");
    exit;
}
function redirectBack($type = null, $message = null) {
    if ($type && $message) {
        $_SESSION[$type] = $message;
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

?>