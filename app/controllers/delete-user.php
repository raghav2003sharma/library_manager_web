<?php
session_start();
require_once "../helpers/helpers.php";
require_once "../models/User.php";
$user = new User();

if (empty($_POST['id'])) {
        redirectBack( "error", "User ID is required.");

}
$user_id = $_POST['id'];

$isDeleted = $user->deleteUserCompletely($user_id);
if ($isDeleted) {
            redirectBack( "success", "User deleted successfully.");

} else {
         redirectBack( "error", "Failed to delete user.");
}

?>