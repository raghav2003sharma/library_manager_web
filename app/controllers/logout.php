<?php
session_start();
session_unset();
session_destroy();
$_SESSION['success'] = "logout success";
header("Location: /public/index.php?page=user-home");
exit;
?>