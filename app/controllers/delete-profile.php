<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/User.php";
$user = new User();

// Must be logged in
if (!isset($_SESSION['user_id'])) {
        redirect("/login","error","You must be logged in first");
}

$user_id = $_SESSION['user_id'];
$isDeleted = $user->deleteUserCompletely($user_id);

if ($isDeleted) {
    session_unset();
    session_destroy();
    redirect("/user-home","success","user deleted successfully");

} else {
        redirect("/user-home","error","Failed to delete the user");

}
