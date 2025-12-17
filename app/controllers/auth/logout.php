<?php
session_start();
session_unset();
session_destroy();
$_SESSION['success'] = "logout success";
header("Location: /user-home");
exit;
?>